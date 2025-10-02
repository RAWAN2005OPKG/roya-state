<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClientPayment;
use App\Models\Alert;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClientPaymentController extends Controller
{
    public function index()
    {
        $payments = ClientPayment::with(['customer'])
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('dashboard.client-payments', compact('payments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'paid_to' => ['nullable', 'string', 'max:100'],
            'paid_to_other' => ['nullable', 'string', 'max:100'],
            'payment_method' => ['required', 'string', 'max:50'],
            'currency' => ['required', 'string', 'max:10'],
            'notes' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'other_bank_name' => ['nullable', 'string', 'max:100'],
            'other_bank_branch' => ['nullable', 'string', 'max:100'],
            'check_number' => ['nullable', 'string', 'max:100'],
            'check_bank' => ['nullable', 'string', 'max:100'],
            'check_due_date' => ['nullable', 'date'],
            'check_receipt_date' => ['nullable', 'date'],
        ]);

        $payment = ClientPayment::create($validated);

        // إنشاء تنبيه إذا كانت الدفعة شيك
        if ($payment->payment_method === 'check' && $payment->check_due_date) {
            Alert::create([
                'title' => 'شيك مستحق للصرف',
                'message' => "شيك رقم {$payment->check_number} بقيمة {$payment->amount} {$payment->currency} مستحق للصرف في تاريخ {$payment->check_due_date}",
                'type' => 'cheque_due',
                'priority' => 'high',
                'status' => 'active',
                'related_id' => $payment->id,
                'related_type' => 'client_payment',
                'due_date' => $payment->check_due_date,
                'created_by' => auth()->id() ?? 1,
            ]);
        }

        return redirect()->route('dashboard.client-payments')->with('success', 'تم حفظ الدفعة بنجاح');
    }

    public function update(Request $request, ClientPayment $clientPayment)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'paid_to' => ['nullable', 'string', 'max:100'],
            'paid_to_other' => ['nullable', 'string', 'max:100'],
            'payment_method' => ['required', 'string', 'max:50'],
            'currency' => ['required', 'string', 'max:10'],
            'notes' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'other_bank_name' => ['nullable', 'string', 'max:100'],
            'other_bank_branch' => ['nullable', 'string', 'max:100'],
            'check_number' => ['nullable', 'string', 'max:100'],
            'check_bank' => ['nullable', 'string', 'max:100'],
            'check_due_date' => ['nullable', 'date'],
            'check_receipt_date' => ['nullable', 'date'],
        ]);

        $clientPayment->update($validated);

        // تحديث التنبيه المرتبط إذا كان شيك
        if ($clientPayment->payment_method === 'check' && $clientPayment->check_due_date) {
            $existingAlert = Alert::where('related_id', $clientPayment->id)
                ->where('related_type', 'client_payment')
                ->where('type', 'cheque_due')
                ->first();

            if ($existingAlert) {
                $existingAlert->update([
                    'message' => "شيك رقم {$clientPayment->check_number} بقيمة {$clientPayment->amount} {$clientPayment->currency} مستحق للصرف في تاريخ {$clientPayment->check_due_date}",
                    'due_date' => $clientPayment->check_due_date,
                ]);
            }
        }

        return redirect()->route('dashboard.client-payments')->with('success', 'تم تحديث الدفعة بنجاح');
    }

    public function destroy(ClientPayment $clientPayment)
    {
        // حذف التنبيه المرتبط إذا كان موجود
        Alert::where('related_id', $clientPayment->id)
            ->where('related_type', 'client_payment')
            ->where('type', 'cheque_due')
            ->delete();

        $clientPayment->delete();
        return redirect()->route('dashboard.client-payments')->with('success', 'تم حذف الدفعة بنجاح');
    }
}
