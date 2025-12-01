<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the bank accounts.
     */
    public function index()
    {
        $bankAccounts = BankAccount::latest()->paginate(10);
        $banks = Bank::where('is_active', true)->pluck('name', 'name');

        $totalAccounts = BankAccount::count();
        $activeAccounts = BankAccount::where('is_active', true)->count();

        return view('dashboard.bank_accounts.index', compact(
            'bankAccounts',
            'banks',
            'totalAccounts',
            'activeAccounts'
        ));
    }

    /**
     * Store a newly created bank account in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|unique:bank_accounts,account_number',
            'initial_balance' => 'required|numeric|min:0',
        ]);

        $validatedData['balance'] = $validatedData['initial_balance'];

        BankAccount::create($validatedData);

        return redirect()->route('dashboard.bank-accounts.index')->with('success', 'تمت إضافة الحساب البنكي بنجاح.');
    }

    /**
     * Display the transaction history for a specific bank account.
     */
    public function show(BankAccount $bankAccount)
    {
        $transactions = $bankAccount->transactions()->latest()->paginate(15);
        $banks = Bank::where('is_active', true)->pluck('name', 'name');

        return view('dashboard.bank_accounts.show', compact('bankAccount', 'transactions', 'banks'));
    }

    /**
     * Show the form for editing the specified bank account.
     * (This is handled by a modal in the index view, so this function is not strictly needed but good to have for consistency)
     */
    public function edit(BankAccount $bankAccount)
    {
        // Redirecting to index as editing is done via modal
        return redirect()->route('dashboard.bank-accounts.index');
    }

    /**
     * Update the specified bank account in storage.
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
     * Remove the specified bank account from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();
        return redirect()->route('dashboard.bank-accounts.index')->with('success', 'تم حذف الحساب البنكي بنجاح.');
    }

    /**
     * Store a new bank transaction.
     */
    public function storeTransaction(Request $request, BankAccount $bankAccount)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:deposit,withdrawal,transfer,personal_withdrawal',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string',
            'date' => 'required|date',
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:255',
            'payer_id_number' => 'nullable|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'transfer_details' => 'nullable|string|max:255',
            'transfer_number' => 'nullable|string|max:255',
            'payer_bank_name' => 'nullable|string|max:255',
            'beneficiary_bank_name' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $transaction = $bankAccount->transactions()->create($validatedData);

        if ($transaction->type == 'deposit') {
            $bankAccount->balance += $transaction->amount;
        } else {
            $bankAccount->balance -= $transaction->amount;
        }
        $bankAccount->save();

        return back()->with('success', 'تم تسجيل الحركة البنكية وتحديث الرصيد بنجاح.');
    }

    /**
     * Show the form for editing a specific transaction.
     */
    public function editTransaction(BankTransaction $transaction)
    {
        $banks = Bank::where('is_active', true)->pluck('name', 'name');

        return view('dashboard.bank_accounts.edit_transaction', compact('transaction', 'banks'));
    }

    /**
     * Update a specific transaction in storage.
     */
    public function updateTransaction(Request $request, BankTransaction $transaction)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:deposit,withdrawal,transfer,personal_withdrawal',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string',
            'date' => 'required|date',
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:255',
            'payer_id_number' => 'nullable|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'transfer_details' => 'nullable|string|max:255',
            'transfer_number' => 'nullable|string|max:255',
            'payer_bank_name' => 'nullable|string|max:255',
            'beneficiary_bank_name' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // ملاحظة: هذا التحديث لا يعيد حساب الرصيد الإجمالي للحساب
        $transaction->update($validatedData);

        return redirect()->route('dashboard.bank-accounts.show', $transaction->bank_account_id)
                         ->with('success', 'تم تعديل الحركة البنكية بنجاح.');
    }
}
