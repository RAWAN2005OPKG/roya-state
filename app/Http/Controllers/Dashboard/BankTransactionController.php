<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BankTransactionController extends Controller
{

    public function index(Request $request)
    {
        $query = BankTransaction::with(['fromAccount.bank', 'toAccount.bank'])->latest('transaction_date');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('details', 'like', "%{$searchTerm}%")
                  ->orWhereHas('fromAccount', fn($fa) => $fa->where('account_name', 'like', "%{$searchTerm}%"))
                  ->orWhereHas('toAccount', fn($ta) => $ta->where('account_name', 'like', "%{$searchTerm}%"));
            });
        }

        $perPage = $request->query('per_page', 10);
        $transactions = $query->paginate($perPage);

        $bankAccounts = BankAccount::with('bank')->get()->each(function ($account) {
            $account->calculateBalance();
        });

        return view('dashboard.bank-transactions.index', compact('transactions', 'bankAccounts', 'request'));
    }

    public function create()
    {
        $bankAccounts = BankAccount::with('bank')->get();
        return view('dashboard.bank-transactions.create', compact('bankAccounts'));
    }

    // ===================================================================
    // ===== هذا هو الجزء الذي تم تعديله بالكامل لحل المشكلة =====
    // ===================================================================
    public function store(Request $request)
    {
        // 1. التحقق من الحقول المشتركة
        $validated = $request->validate([
            'type' => 'required|in:deposit,withdrawal,transfer',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'details' => 'nullable|string',
        ]);

        // 2. التحقق من الحقول المعتمدة على النوع
        if ($request->type === 'deposit' || $request->type === 'withdrawal') {
            $request->validate(['bank_account_id' => 'required|exists:bank_accounts,id']);
        } elseif ($request->type === 'transfer') {
            $request->validate([
                'from_account_id' => 'required|exists:bank_accounts,id',
                'to_account_id' => 'required|exists:bank_accounts,id|different:from_account_id',
            ]);
        }

        try {
            DB::transaction(function () use ($request, $validated) {
                // 3. التعامل مع كل حالة على حدة
                if ($validated['type'] === 'transfer') {
                    $fromAccount = BankAccount::find($request->from_account_id);
                    $toAccount = BankAccount::find($request->to_account_id);

                    // حركة السحب من الحساب الأول
                    $withdrawal = BankTransaction::create([
                        'type' => 'withdrawal',
                        'status' => 'completed',
                        'transaction_date' => $validated['transaction_date'],
                        'amount' => $validated['amount'],
                        'currency' => $fromAccount->currency, // عملة الحساب المصدر
                        'from_account_id' => $fromAccount->id,
                        'details' => $validated['details'] . ' (تحويل إلى حساب ' . $toAccount->account_name . ')',
                    ]);

                    // حركة الإيداع في الحساب الثاني
                    $deposit = BankTransaction::create([
                        'type' => 'deposit',
                        'status' => 'completed',
                        'transaction_date' => $validated['transaction_date'],
                        'amount' => $validated['amount'],
                        'currency' => $toAccount->currency, // عملة الحساب المستقبل
                        'to_account_id' => $toAccount->id,
                        'details' => $validated['details'] . ' (تحويل من حساب ' . $fromAccount->account_name . ')',
                        'related_transaction_id' => $withdrawal->id,
                    ]);

                    // ربط الحركتين ببعضهما
                    $withdrawal->update(['related_transaction_id' => $deposit->id]);

                } else { // حالة الإيداع أو السحب
                    $account = BankAccount::find($request->bank_account_id);
                    BankTransaction::create([
                        'type' => $validated['type'],
                        'status' => 'completed',
                        'transaction_date' => $validated['transaction_date'],
                        'amount' => $validated['amount'],
                        'currency' => $account->currency, // عملة الحساب المحدد
                        'from_account_id' => $validated['type'] === 'withdrawal' ? $account->id : null,
                        'to_account_id' => $validated['type'] === 'deposit' ? $account->id : null,
                        'details' => $validated['details'],
                    ]);
                }
            });

            return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم حفظ الحركة بنجاح.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'فشل حفظ الحركة: ' . $e->getMessage());
        }
    }

    public function update(Request $request, BankTransaction $bankTransaction)
    {
        $validated = $request->validate([
            'type' => 'required|in:deposit,withdrawal', // التحديث للحوالات أكثر تعقيداً، سنركز على الحركات البسيطة
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'details' => 'nullable|string',
            'bank_account_id' => 'required|exists:bank_accounts,id',
        ]);

        try {
            DB::transaction(function () use ($validated, $bankTransaction) {
                // الخطوة 1: التراجع عن أثر الحركة القديمة (هذه الدالة غير موجودة في لارافيل، يجب أن نكتبها)
                // هذه الخطوة غير ضرورية إذا كنا سنعيد حساب الرصيد من الصفر دائماً، وهو الأسلوب الآمن

                // الخطوة 2: تحديث بيانات الحركة
                $bankTransaction->update([
                    'type' => $validated['type'],
                    'transaction_date' => $validated['transaction_date'],
                    'amount' => $validated['amount'],
                    'details' => $validated['details'],
                    // تحديث الحسابات المرتبطة
                    'from_account_id' => $validated['type'] === 'withdrawal' ? $validated['bank_account_id'] : null,
                    'to_account_id' => $validated['type'] === 'deposit' ? $validated['bank_account_id'] : null,
                ]);

                // الخطوة 3: إعادة حساب الرصيد (غير ضروري هنا لأن دالة index تقوم به)
            });

            return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم تحديث الحركة بنجاح.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'فشل تحديث الحركة: ' . $e->getMessage());
        }
    }
    public function show(BankTransaction $bankTransaction)
    {
        $bankTransaction->load(['fromAccount.bank', 'toAccount.bank']);
        return view('dashboard.bank-transactions.show', compact('bankTransaction'));
    }

    public function edit(BankTransaction $bankTransaction)
    {
        $bankAccounts = BankAccount::with('bank')->get();
        // لتبسيط النموذج، سنجمع الحسابات في حقل واحد
        $bankTransaction->bank_account_id = $bankTransaction->from_account_id ?? $bankTransaction->to_account_id;
        return view('dashboard.bank-transactions.edit', compact('bankTransaction', 'bankAccounts'));
    }

    public function destroy(BankTransaction $bankTransaction)
    {
        $bankTransaction->delete();
        return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم نقل الحركة إلى سلة المهملات.');
    }

    public function trash()
    {
        $trashedTransactions = BankTransaction::onlyTrashed()->with(['fromAccount', 'toAccount'])->latest('deleted_at')->paginate(10);
        return view('dashboard.bank-transactions.trash', compact('trashedTransactions'));
    }

    public function restore($id)
    {
        BankTransaction::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'تم استعادة الحركة بنجاح.');
    }

    public function forceDelete($id)
    {
        BankTransaction::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'تم حذف الحركة نهائياً.');
    }
}
