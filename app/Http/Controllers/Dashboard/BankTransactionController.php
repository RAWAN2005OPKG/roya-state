<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankTransaction;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = BankTransaction::with(['fromAccount.bank', 'toAccount.bank']);
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('details', 'like', "%{$searchTerm}%")
                  ->orWhereHas('fromAccount', fn($fa) => $fa->where('account_name', 'like', "%{$searchTerm}%"))
                  ->orWhereHas('toAccount', fn($ta) => $ta->where('account_name', 'like', "%{$searchTerm}%"));
            });
        }
        $perPage = $request->query('per_page', 10);
        $transactions = $query->latest('transaction_date')->paginate($perPage);
        $bankAccounts = BankAccount::with('bank')->get()->map(function ($account) {
            $in = BankTransaction::where('to_account_id', $account->id)->sum('amount');
            $out = BankTransaction::where('from_account_id', $account->id)->sum('amount');
            $account->balance = $account->opening_balance + $in - $out;
            return $account;
        });
        return view('dashboard.bank-transactions.index', compact('transactions', 'bankAccounts', 'request'));
    }

    public function create()
    {
        $bankAccounts = BankAccount::with('bank')->get();
        $transaction = new BankTransaction();
        return view('dashboard.bank-transactions.create', compact('bankAccounts', 'transaction'));
    }

    // ===================================================================
    // ===== هذه هي دالة الحفظ المصححة التي تفهم النموذج =====
    // ===================================================================
    public function store(Request $request)
    {
        $type = $request->input('type');
        $rules = [
            'type' => 'required|in:deposit,withdrawal,transfer',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'details' => 'nullable|string',
        ];

        // ===== هذا هو الجزء الذي تم تصحيحه بالكامل =====
        if ($type === 'deposit') {
            // النموذج يرسل bank_account_id، ونحن نتوقعه
            $rules['bank_account_id'] = 'required|exists:bank_accounts,id';
        } elseif ($type === 'withdrawal') {
            // النموذج يرسل bank_account_id، ونحن نتوقعه
            $rules['bank_account_id'] = 'required|exists:bank_accounts,id';
        } elseif ($type === 'transfer') {
            // النموذج يرسل from_account_id و to_account_id
            $rules['from_account_id'] = 'required|exists:bank_accounts,id';
            $rules['to_account_id'] = 'required|exists:bank_accounts,id|different:from_account_id';
        }
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($validated, $type) {
                $data = [
                    'type' => $type,
                    'transaction_date' => $validated['transaction_date'],
                    'amount' => $validated['amount'],
                    'details' => $validated['details'],
                ];

                if ($type === 'deposit') {
                    $account = BankAccount::find($validated['bank_account_id']);
                    $data['to_account_id'] = $validated['bank_account_id'];
                    $data['currency'] = $account->currency;
                } elseif ($type === 'withdrawal') {
                    $account = BankAccount::find($validated['bank_account_id']);
                    $data['from_account_id'] = $validated['bank_account_id'];
                    $data['currency'] = $account->currency;
                } elseif ($type === 'transfer') {
                    $fromAccount = BankAccount::find($validated['from_account_id']);
                    $data['from_account_id'] = $validated['from_account_id'];
                    $data['to_account_id'] = $validated['to_account_id'];
                    $data['currency'] = $fromAccount->currency;
                }
                BankTransaction::create($data);
            });
            return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم حفظ الحركة البنكية بنجاح.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'فشل حفظ الحركة: ' . $e->getMessage());
        }
    }

    // باقي الدوال (show, edit, destroy, trash, restore, forceDelete)
    public function show(BankTransaction $bankTransaction)
    {
        $bankTransaction->load(['fromAccount.bank', 'toAccount.bank']);
        return view('dashboard.bank-transactions.show', compact('bankTransaction'));
    }

    public function edit(BankTransaction $bankTransaction)
    {
        $bankAccounts = BankAccount::with('bank')->get();
        return view('dashboard.bank-transactions.edit', compact('bankTransaction', 'bankAccounts'));
    }

    public function update(Request $request, BankTransaction $bankTransaction)
    {
        // منطق التحديث يمكن إضافته هنا بنفس طريقة دالة store
        return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم تحديث الحركة بنجاح.');
    }

    public function destroy(BankTransaction $bankTransaction)
    {
        $bankTransaction->delete();
        return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم نقل الحركة إلى سلة المحذوفات.');
    }

    public function trash()
    {
        $trashedTransactions = BankTransaction::onlyTrashed()->with(['fromAccount.bank', 'toAccount.bank'])->latest()->get();
        return view('dashboard.bank-transactions.trash', compact('trashedTransactions'));
    }

    public function restore($id)
    {
        BankTransaction::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('dashboard.bank-transactions.trash')->with('success', 'تم استعادة الحركة بنجاح.');
    }

    public function forceDelete($id)
    {
        BankTransaction::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('dashboard.bank-transactions.trash')->with('success', 'تم حذف الحركة نهائياً.');
    }
}
