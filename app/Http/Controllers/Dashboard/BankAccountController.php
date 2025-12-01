<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bankAccounts = BankAccount::latest()->paginate(10);
        return view('dashboard.bank_accounts.index', compact('bankAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|unique:bank_accounts,account_number',
            'initial_balance' => 'required|numeric|min:0',
        ]);

        $validatedData['balance'] = $validatedData['initial_balance']; // الرصيد الحالي يبدأ بالافتتاحي

        BankAccount::create($validatedData);

        return redirect()->route('dashboard.bank-accounts.index')->with('success', 'تمت إضافة الحساب البنكي بنجاح.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankAccount $bankAccount)
    {
        return view('dashboard.bank_accounts.edit', compact('bankAccount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $validatedData = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|unique:bank_accounts,account_number,' . $bankAccount->id,
            'is_active' => 'required|boolean',
        ]);

        $bankAccount->update($validatedData);

        return redirect()->route('dashboard.bank-accounts.index')->with('success', 'تم تعديل الحساب البنكي بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();
        return redirect()->route('dashboard.bank-accounts.index')->with('success', 'تم حذف الحساب البنكي بنجاح.');
    }
}
