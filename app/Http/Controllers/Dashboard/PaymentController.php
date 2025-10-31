<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Fund;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log; // تم إضافة هذا السطر

class PaymentController extends Controller
{
    /**
     * عرض نموذج إضافة دفعة جديدة لعقد معين.
     */
    public function create(Contract $contract)
    {
        // حساب المبلغ المتبقي وتمريره للعرض
        $remaining = $contract->investment_amount - $contract->total_paid;
        $funds = Fund::orderBy('name')->get();
        return view('dashboard.payments.create', compact('contract', 'funds', 'remaining'));
    }

    /**
     * حفظ دفعة جديدة في قاعدة البيانات.
     */
    public function store(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'fund_id' => ['required', 'exists:funds,id'],
        ]);

        DB::beginTransaction();
        try {
            $remaining = $contract->investment_amount - $contract->total_paid;

            // التحقق من المبلغ المدفوع
            if ($validated['amount'] > $remaining) {
                DB::rollBack();
                return back()->with('error', 'المبلغ المدفوع يتجاوز المبلغ المتبقي على العقد.')->withInput();
            }

            // 1. إنشاء الدفعة
            $contract->payments()->create($validated);

            // 2. تحديث حقل total_paid في العقد (التصحيح الرئيسي)
            $contract->total_paid += $validated['amount'];
            $contract->save();

            DB::commit();
            return redirect()->route('dashboard.contracts.show', $contract->id)->with('success', 'تم تسجيل الدفعة بنجاح.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Payment store error: " . $e->getMessage(), ['contract_id' => $contract->id, 'request' => $request->all()]);
            return back()->with('error', 'حدث خطأ أثناء تسجيل الدفعة. يرجى مراجعة سجلات النظام.')->withInput();
        }
    }

    /**
     * حذف دفعة معينة.
     */
    public function destroy(Contract $contract, Payment $payment)
    {
        // التحقق من أن الدفعة تابعة للعقد
        if ($payment->contract_id !== $contract->id) {
            return back()->with('error', 'الدفعة غير مرتبطة بهذا العقد.')->withInput();
        }

        DB::beginTransaction();
        try {
            // 1. تحديث حقل total_paid في العقد (التصحيح الثاني)
            $contract->total_paid -= $payment->amount;
            $contract->save();

            // 2. حذف الدفعة
            $payment->delete();

            DB::commit();
            return back()->with('success', 'تم حذف الدفعة بنجاح.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Payment destroy error: " . $e->getMessage(), ['payment_id' => $payment->id]);
            return back()->with('error', 'حدث خطأ أثناء حذف الدفعة. يرجى مراجعة سجلات النظام.')->withInput();
        }
    }
}
