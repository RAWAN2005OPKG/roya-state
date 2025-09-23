<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investment;

class InvestmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'investor_id' => ['required', 'exists:investors,id'],
            'date' => ['required', 'date'],
            'project' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'id_number' => ['nullable', 'string', 'max:100'],
            'job' => ['nullable', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:10'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'share_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'status' => ['nullable', 'in:active,completed,cancelled'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'payee' => ['nullable', 'string', 'max:255'],
            'payment_date' => ['nullable', 'date'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'other_bank_name' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'contract_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        Investment::create($validated);

        return back()->with('success', 'تم حفظ الاستثمار بنجاح');
    }
}
