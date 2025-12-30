<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Investor;
use App\Models\Payment;
use App\Models\Bank;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * عرض واجهة القيود اليومية (إضافة دفعة).
     */
    public function create()
    {
        $clients = Client::select('id', 'name', 'unique_id')->get();
        $investors = Investor::select('id', 'name', 'unique_id')->get();
        $banks = Bank::all(); // جلب البنوك
        $bankAccounts = BankAccount::all(); // جلب الحسابات البنكية

        return view('dashboard.payments.create', compact('clients', 'investors', 'banks', 'bankAccounts'));
    }

    /**
     * حساب القيمة المعادلة بالشيكل.
     */
    private function calculateILSAmount($amount, $currency, $exchangeRate)
    {
        if ($currency === 'ILS') {
            return $amount;
        }
        return $amount * $exchangeRate;
    }

    /**
     * حفظ الدفعة الجديدة.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payable_type' => 'required|in:Client,Investor',
            'payable_id' => 'required|integer',
            'type' => 'required|in:in,out',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|in:ILS,USD,JOD',
            'exchange_rate' => 'required_if:currency,USD,JOD|numeric|min:0.01',
            'method' => 'required|in:cash,bank_transfer,check',

            // قواعد خاصة بطريقة الدفع
            'check_number' => 'required_if:method,check|nullable|string|max:255',
            'due_date' => 'required_if:method,check|nullable|date',
            'check_owner' => 'required_if:method,check|nullable|string|max:255',
            'sender_bank_account_id' => 'required_if:method,bank_transfer|nullable|exists:bank_accounts,id',
            'receiver_bank_account_id' => 'required_if:method,bank_transfer|nullable|exists:bank_accounts,id',
            'delivered_by' => 'required_if:method,cash|nullable|string|max:255',
            'received_by' => 'required_if:method,cash|nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 1. تحديد الكيان القابل للدفع له/منه
            $payableModel = 'App\\Models\\' . $request->payable_type;
            $payable = $payableModel::findOrFail($request->payable_id);

            // 2. حساب القيمة بالشيكل
            $amountILS = $this->calculateILSAmount(
                $request->amount,
                $request->currency,
                $request->exchange_rate ?? 1
            );

            // 3. حفظ الدفعة
            $payment = $payable->payments()->create([
                'type' => $request->type,
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'exchange_rate' => $request->exchange_rate ?? 1,
                'amount_ils' => $amountILS,
                'method' => $request->method,

                // تفاصيل الشيك
                'check_number' => $request->check_number,
                'due_date' => $request->due_date,
                'check_owner' => $request->check_owner,
                'check_type' => $request->check_type,

                // تفاصيل التحويل البنكي
                'sender_bank_account_id' => $request->sender_bank_account_id,
                'receiver_bank_account_id' => $request->receiver_bank_account_id,
                'transaction_reference' => $request->transaction_reference,

                // تفاصيل النقد
                'delivered_by' => $request->delivered_by,
                'received_by' => $request->received_by,
                'notes' => $request->notes,
            ]);

            // 4. (خطوة إضافية) تحديث رصيد الحساب البنكي إذا كان تحويلاً
            // هذه الخطوة تتطلب منطقاً إضافياً في نظامك المحاسبي

            DB::commit();

            return redirect()->route('dashboard.payments.create')
                ->with('success', 'تم تسجيل الدفعة بنجاح. القيمة بالشيكل: ' . number_format($amountILS, 2) . ' ILS');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء تسجيل الدفعة: ' . $e->getMessage());
        }
    }
}
