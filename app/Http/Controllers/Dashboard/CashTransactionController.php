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
    public function index() {
        $transactions = BankTransaction::with('bankAccount.bank')->latest()->paginate(15);
        return view('dashboard.bank_transactions.index', compact('transactions'));
    }

    public function create() {
        $bankAccounts = BankAccount::where('is_active', true)->with('bank')->get();
        $banks = Bank::all();
        return view('dashboard.bank_transactions.create', compact('bankAccounts', 'banks'));
    }

    public function store(Request $request) {
        $validatedData = $request->validate([/*...قواعد التحقق هنا...*/]);

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

    public function edit(BankTransaction $bankTransaction) {
        $bankAccounts = BankAccount::where('is_active', true)->with('bank')->get();
        $banks = Bank::all();
        return view('dashboard.bank_transactions.edit', ['transaction' => $bankTransaction, 'bankAccounts' => $bankAccounts, 'banks' => $banks]);
    }

    public function update(Request $request, BankTransaction $bankTransaction) {
        $validatedData = $request->validate([/*...قواعد التحقق هنا...*/]);

        // ... (منطق معقد لتحديث الرصيد عند التعديل) ...

        $bankTransaction->update($validatedData);
        return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم تعديل الحركة بنجاح.');
    }

    public function destroy(BankTransaction $bankTransaction) {
        // ... (منطق معقد لإعادة الرصيد عند الحذف) ...
        $bankTransaction->delete();
        return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم نقل الحركة لسلة المحذوفات.');
    }

}
