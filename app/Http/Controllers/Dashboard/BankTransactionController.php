<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankTransaction;
use App\Models\BankAccount;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankTransactionController extends Controller
{
    public function index()
    {
        $transactions = BankTransaction::with('bankAccount.bank')->latest()->paginate(15);
        return view('dashboard.bank_transactions.index', compact('transactions'));
    }

    public function create()
    {
        $bankAccounts = BankAccount::where('is_active', true)->with('bank')->get();
        $banks = Bank::all();
        return view('dashboard.bank_transactions.create', compact('bankAccounts', 'banks'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'date' => 'required|date',
            'type' => 'required|in:deposit,withdrawal',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validatedData) {
            $transaction = BankTransaction::create($validatedData);
            $account = $transaction->bankAccount;
            if ($transaction->type === 'deposit') {
                $account->balance += $transaction->amount;
            } else {
                $account->balance -= $transaction->amount;
            }
            $account->save();
        });

        return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم تسجيل الحركة وتحديث الرصيد.');
    }

    public function edit(BankTransaction $bankTransaction)
    {
        $bankAccounts = BankAccount::where('is_active', true)->with('bank')->get();
        $banks = Bank::all();
        return view('dashboard.bank_transactions.edit', ['transaction' => $bankTransaction, 'bankAccounts' => $bankAccounts, 'banks' => $banks]);
    }

    public function update(Request $request, BankTransaction $bankTransaction)
    {
        // ... (منطق التحديث مع تحديث الرصيد) ...
        $bankTransaction->update($request->all());
        return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم تعديل الحركة بنجاح.');
    }

    public function destroy(BankTransaction $bankTransaction)
    {
        DB::transaction(function () use ($bankTransaction) {
            $account = $bankTransaction->bankAccount;
            // عكس العملية المالية قبل الحذف
            if ($bankTransaction->type === 'deposit') {
                $account->balance -= $bankTransaction->amount;
            } else { // withdrawal
                $account->balance += $bankTransaction->amount;
            }
            $account->save();
            $bankTransaction->delete(); // حذف ناعم
        });

        return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم نقل الحركة لسلة المحذوفات وتعديل الرصيد.');
    }

    public function trash()
    {
        $transactions = BankTransaction::onlyTrashed()->with('bankAccount.bank')->latest()->paginate(15);
        return view('dashboard.bank_transactions.trash', compact('transactions'));
    }

    public function restore($id)
    {
        $transaction = BankTransaction::onlyTrashed()->findOrFail($id);
        DB::transaction(function () use ($transaction) {
            $account = $transaction->bankAccount;
            // إعادة تطبيق العملية المالية عند الاسترجاع
            if ($transaction->type === 'deposit') {
                $account->balance += $transaction->amount;
            } else { // withdrawal
                $account->balance -= $transaction->amount;
            }
            $account->save();
            $transaction->restore();
        });
        return redirect()->route('dashboard.bank-transactions.trash')->with('success', 'تم استرجاع الحركة وتحديث الرصيد.');
    }

    public function forceDelete($id)
    {
        $transaction = BankTransaction::onlyTrashed()->findOrFail($id);
        $transaction->forceDelete();
        return redirect()->route('dashboard.bank-transactions.trash')->with('success', 'تم حذف الحركة نهائياً.');
    }
}
