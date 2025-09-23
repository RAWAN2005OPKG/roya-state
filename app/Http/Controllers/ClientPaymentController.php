<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientPayment;

class ClientPaymentController extends Controller
{
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
            // bank
            'bank_name' => ['nullable', 'string', 'max:100'],
            'other_bank_name' => ['nullable', 'string', 'max:100'],
            'other_bank_branch' => ['nullable', 'string', 'max:100'],
            // check
            'check_number' => ['nullable', 'string', 'max:100'],
            'check_bank' => ['nullable', 'string', 'max:100'],
            'check_due_date' => ['nullable', 'date'],
            'check_receipt_date' => ['nullable', 'date'],
        ]);

        ClientPayment::create($validated);

        return back()->with('success', 'تم حفظ الدفعة بنجاح');
    }
}
