<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FundsTransfer; 

class FundsTransferController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'from_account' => ['required', 'string', 'in:cash,cheques,bank'],
            'to_account' => ['required', 'string', 'in:cash,cheques,bank', 'different:from_account'],
            'name' => ['required', 'string', 'max:255'],
            'id_number' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'currency' => ['required', 'string', 'max:10'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ]);

        FundsTransfer::create($validated);

        return redirect()->route('dashboard.treasury')->with('success', 'تم تسجيل عملية التحويل بنجاح!');
    }
}
