<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;

class ContractController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            // بيانات العقد
            'contract_id' => ['required', 'string', 'max:255'],
            'signing_date' => ['required', 'date'],
            'status' => ['nullable', 'in:active,draft'],

            // بيانات المستثمر
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_phone' => ['required', 'string', 'max:50'],
            'client_alt_phone' => ['nullable', 'string', 'max:50'],
            'client_id_number' => ['required', 'string', 'max:100'],

            // بيانات العقار
            'property_type' => ['nullable', 'string', 'max:255'],
            'property_location' => ['nullable', 'string', 'max:255'],

            // التفاصيل المالية
            'investment_amount' => ['required', 'numeric', 'min:0'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,bank_transaction,check'],
            'apartment_price' => ['nullable', 'numeric', 'min:0'],
            'first_payment_date' => ['nullable', 'date'],
            'down_payment_initial' => ['nullable', 'numeric', 'min:0'],
            'down_payment_other' => ['nullable', 'numeric', 'min:0'],
            'profit_percentage' => ['nullable', 'numeric', 'min:0'],
            'remaining_amount' => ['nullable', 'numeric', 'min:0'],

            // تفاصيل الدفع النقدي
            'cash_receiver' => ['nullable', 'string', 'max:255'],
            'cash_receiver_other' => ['nullable', 'string', 'max:255'],
            'cash_receiver_job' => ['nullable', 'string', 'max:255'],
            'cash_receipt_date' => ['nullable', 'date'],

            // تفاصيل البنك
            'sender_bank' => ['nullable', 'string', 'max:255'],
            'sender_bank_other' => ['nullable', 'string', 'max:255'],
            'sender_bank_branch' => ['nullable', 'string', 'max:255'],
            'receiver_bank' => ['nullable', 'string', 'max:255'],
            'receiver_bank_other' => ['nullable', 'string', 'max:255'],
            'receiver_bank_branch' => ['nullable', 'string', 'max:255'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['nullable', 'date'],

            // تفاصيل الشيك
            'check_number' => ['nullable', 'string', 'max:255'],
            'check_owner' => ['nullable', 'string', 'max:255'],
            'check_holder' => ['nullable', 'string', 'max:255'],
            'check_bank' => ['nullable', 'string', 'max:255'],
            'check_bank_other' => ['nullable', 'string', 'max:255'],
            'check_bank_branch' => ['nullable', 'string', 'max:255'],
            'check_due_date' => ['nullable', 'date'],
            'check_receipt_date' => ['nullable', 'date'],
        ]);

        Contract::create($validated);

        return back()->with('success', 'تم حفظ العقد بنجاح');
    }
}
