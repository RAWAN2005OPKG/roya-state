<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'due_date' => ['nullable', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'project' => ['nullable', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'agreement_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'],
            'currency' => ['required', 'string', 'max:10'],
            'paid_to' => ['nullable', 'string', 'max:100'],
            'paid_to_other' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'other_bank_name' => ['nullable', 'string', 'max:100'],
            'other_bank_branch' => ['nullable', 'string', 'max:100'],
            'check_number' => ['nullable', 'string', 'max:100'],
            'check_bank' => ['nullable', 'string', 'max:100'],
            'check_due_date' => ['nullable', 'date'],
            'check_receipt_date' => ['nullable', 'date'],
            'contract_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);

        if ($request->hasFile('contract_file')) {
            $validated['contract_file'] = $request->file('contract_file')->store('contracts', 'public');
        }

        Customer::create($validated);

        return back()->with('success', 'تم حفظ العميل بنجاح');
    }
}
