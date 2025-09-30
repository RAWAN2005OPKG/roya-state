<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FundTransfer;

class FundTransferController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'from_account' => ['required', 'string', 'max:50'],
            'to_account' => ['required', 'string', 'max:50', 'different:from_account'],
            'name' => ['required', 'string', 'max:255'],
            'id_number' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'currency' => ['required', 'string', 'max:10'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ]);

        FundTransfer::create($validated);


        return redirect()->route('dashboard.prbancascheq')->with('success', 'تم الإضافة  بنجاح!');
    }
}
