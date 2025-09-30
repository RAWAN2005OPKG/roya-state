<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cheque;

class ChequeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cheque_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'type' => ['required', 'in:incoming,outgoing'],
            'cheque_number' => ['nullable', 'string', 'max:255'],
            'transfer_number' => ['nullable', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'holder_name' => ['nullable', 'string', 'max:255'],
            'payer_id_number' => ['nullable', 'string', 'max:100'],
            'client_phone' => ['nullable', 'string', 'max:50'],
            'beneficiary_name' => ['nullable', 'string', 'max:255'],
            'project_name' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'other_bank_name' => ['nullable', 'string', 'max:255'],
            'bank_branch' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'operator' => ['nullable', 'string', 'max:255'],
            'transfer_details' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:in_wallet,cashed,returned'],
        ]);

        Cheque::create($validated);


        return redirect()->route('dashboard.prbancascheq')->with('success', 'تم الإضافة  بنجاح!');
    }
}
