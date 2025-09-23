<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentVoucher;
use Illuminate\Support\Facades\Validator;

class PaymentVoucherController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // قواعد التحقق من صحة البيانات المدخلة من النموذج
        $validator = Validator::make($request->all(), [
            'transaction_date' => ['required', 'date'],
            'contact_id' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:10'],
            'payment_method' => ['required', 'in:cash,bank_transaction,check'],
            'purpose_description' => ['required', 'string'], // سبب الصرف إلزامي
            'project_id' => ['nullable', 'integer'],
            'receiver_name' => ['required', 'string', 'max:255'], // اسم المسلّم (الصارف)

            // الحقول الديناميكية (اختيارية)
            'cash_receiver' => ['nullable', 'string', 'max:255'],
            'cash_receiver_job' => ['nullable', 'string', 'max:255'],
            'sender_bank' => ['nullable', 'string', 'max:255'],
            'receiver_bank' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'check_number' => ['nullable', 'string', 'max:255'],
            'check_owner' => ['nullable', 'string', 'max:255'],
            'check_due_date' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        PaymentVoucher::create($validator->validated());

        return redirect()->route('dashboard.cash')
                         ->with('success', 'تم حفظ سند الصرف بنجاح!');
    }
}
