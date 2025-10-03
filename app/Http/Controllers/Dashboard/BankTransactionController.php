<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankTransaction;

class BankTransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'client_phone' => ['nullable', 'string', 'max:50'],
            'payer_id_number' => ['nullable', 'string', 'max:100'],
            'type' => ['required', 'in:deposit,withdrawal,transfer,personal_withdrawal'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:10'],
            'project_name' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'transfer_details' => ['nullable', 'string', 'max:255'],
            'transfer_number' => ['nullable', 'string', 'max:255'],
            'beneficiary_name' => ['nullable', 'string', 'max:255'],
            'beneficiary_bank_name' => ['nullable', 'string', 'max:255'],
            'beneficiary_bank_number' => ['nullable', 'string', 'max:255'],
            'cheque_number' => ['nullable', 'string', 'max:255'],
            'cheque_owner_name' => ['nullable', 'string', 'max:255'],
            'payer_bank_name' => ['nullable', 'string', 'max:255'],
            'payer_bank_number' => ['nullable', 'string', 'max:255'],
            'operator' => ['required', 'string', 'max:255'],
            'operator_role' => ['nullable', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'other_bank_name' => ['nullable', 'string', 'max:255'],
            'details' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        BankTransaction::create($validated);


        return redirect()->route('dashboard.bank.index')->with('success', 'تم تسجيل الحركة البنكية بنجاح!');
    }
}



