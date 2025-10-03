<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashTransaction;

class CashTransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'type' => ['required', 'in:deposit,withdrawal,personal_withdrawal'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:10'],
            'project_name' => ['nullable', 'string', 'max:255'],
            'beneficiary' => ['nullable', 'string', 'max:255'],
            'operator' => ['required', 'string', 'max:255'],
            'operator_role' => ['nullable', 'string', 'max:255'],
            'details' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'payer_id_number' => ['nullable', 'string', 'max:100'],
            'client_phone' => ['nullable', 'string', 'max:50'],
        ]);

        CashTransaction::create($validated);

        return redirect()->route('dashboard.cash.index')->with('success', 'تم تسجيل حركة الكاش بنجاح!');
    }
}
