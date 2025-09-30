<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Cheque;
use App\Models\Contract;
use App\Models\Investment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::with(['creator', 'assignee'])
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.alernt', compact('alerts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:cheque_due,contract_expiry,payment_due,general',
            'priority' => 'required|in:high,medium,low',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        Alert::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'priority' => $request->priority,
            'status' => 'active',
            'due_date' => $request->due_date,
            'created_by' => auth()->id(),
            'assigned_to' => $request->assigned_to,
        ]);

        return redirect()->route('dashboard.alerts')->with('success', 'تم إنشاء التنبيه بنجاح');
    }

    public function update(Request $request, Alert $alert)
    {
        $request->validate([
            'status' => 'required|in:active,dismissed,resolved',
        ]);

        $alert->update([
            'status' => $request->status,
        ]);

        return redirect()->route('dashboard.alerts')->with('success', 'تم تحديث التنبيه بنجاح');
    }

    public function destroy(Alert $alert)
    {
        $alert->delete();
        return redirect()->route('dashboard.alerts')->with('success', 'تم حذف التنبيه بنجاح');
    }

    // دالة لإنشاء تنبيهات الشيكات المستحقة
    public function generateChequeAlerts()
    {
        $upcomingCheques = Cheque::where('status', 'in_wallet')
            ->where('due_date', '<=', Carbon::now()->addDays(7))
            ->where('due_date', '>=', Carbon::now())
            ->get();

        foreach ($upcomingCheques as $cheque) {
            // تحقق من وجود تنبيه مسبق لهذا الشيك
            $existingAlert = Alert::where('related_id', $cheque->id)
                ->where('related_type', 'cheque')
                ->where('type', 'cheque_due')
                ->where('status', 'active')
                ->first();

            if (!$existingAlert) {
                Alert::create([
                    'title' => 'شيك مستحق للصرف',
                    'message' => "الشيك رقم {$cheque->cheque_number} للعميل {$cheque->owner_name} مستحق للصرف في تاريخ {$cheque->due_date->format('Y-m-d')}",
                    'type' => 'cheque_due',
                    'priority' => 'high',
                    'status' => 'active',
                    'related_id' => $cheque->id,
                    'related_type' => 'cheque',
                    'due_date' => $cheque->due_date,
                    'created_by' => 1, // Admin user
                ]);
            }
        }

        return $upcomingCheques->count();
    }

    // دالة لإنشاء تنبيهات العقود المنتهية الصلاحية
    public function generateContractAlerts()
    {
        $expiringContracts = Contract::where('status', 'active')
            ->whereNotNull('first_payment_date')
            ->whereNotNull('duration_months')
            ->get()
            ->filter(function ($contract) {
                $endDate = Carbon::parse($contract->first_payment_date)->addMonths($contract->duration_months);
                return $endDate->lte(Carbon::now()->addDays(30)) && $endDate->gte(Carbon::now());
            });

        foreach ($expiringContracts as $contract) {
            $endDate = Carbon::parse($contract->first_payment_date)->addMonths($contract->duration_months);
            
            // تحقق من وجود تنبيه مسبق لهذا العقد
            $existingAlert = Alert::where('related_id', $contract->id)
                ->where('related_type', 'contract')
                ->where('type', 'contract_expiry')
                ->where('status', 'active')
                ->first();

            if (!$existingAlert) {
                Alert::create([
                    'title' => 'عقد منتهي الصلاحية',
                    'message' => "العقد رقم {$contract->contract_id} للعميل {$contract->client_name} سينتهي في تاريخ {$endDate->format('Y-m-d')}",
                    'type' => 'contract_expiry',
                    'priority' => 'medium',
                    'status' => 'active',
                    'related_id' => $contract->id,
                    'related_type' => 'contract',
                    'due_date' => $endDate,
                    'created_by' => 1, // Admin user
                ]);
            }
        }

        return $expiringContracts->count();
    }

    // دالة لإنشاء تنبيهات الدفعات المستحقة
    public function generatePaymentAlerts()
    {
        $upcomingPayments = Investment::where('status', 'active')
            ->whereNotNull('payment_date')
            ->where('payment_date', '<=', Carbon::now()->addDays(7))
            ->where('payment_date', '>=', Carbon::now())
            ->get();

        foreach ($upcomingPayments as $investment) {
            // تحقق من وجود تنبيه مسبق لهذا الاستثمار
            $existingAlert = Alert::where('related_id', $investment->id)
                ->where('related_type', 'investment')
                ->where('type', 'payment_due')
                ->where('status', 'active')
                ->first();

            if (!$existingAlert) {
                Alert::create([
                    'title' => 'دفعة مستحقة',
                    'message' => "دفعة بقيمة {$investment->amount} للمشروع {$investment->project} مستحقة في تاريخ {$investment->payment_date->format('Y-m-d')}",
                    'type' => 'payment_due',
                    'priority' => 'high',
                    'status' => 'active',
                    'related_id' => $investment->id,
                    'related_type' => 'investment',
                    'due_date' => $investment->payment_date,
                    'created_by' => 1, // Admin user
                ]);
            }
        }

        return $upcomingPayments->count();
    }

    // دالة لتحديث جميع التنبيهات
    public function refreshAlerts()
    {
        $chequeAlerts = $this->generateChequeAlerts();
        $contractAlerts = $this->generateContractAlerts();
        $paymentAlerts = $this->generatePaymentAlerts();

        $totalAlerts = $chequeAlerts + $contractAlerts + $paymentAlerts;

        return redirect()->route('dashboard.alerts')->with('success', "تم تحديث التنبيهات بنجاح. تم إنشاء {$totalAlerts} تنبيه جديد");
    }

    // دالة للحصول على عدد التنبيهات النشطة
    public function getActiveAlertsCount()
    {
        return Alert::where('status', 'active')->count();
    }

    // دالة للحصول على التنبيهات عالية الأولوية
    public function getHighPriorityAlerts()
    {
        return Alert::where('status', 'active')
            ->where('priority', 'high')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();
    }
}
