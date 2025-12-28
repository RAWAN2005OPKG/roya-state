<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankTransaction;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BankTransactionController extends Controller
{
    /**
     * عرض قائمة بكل الحركات البنكية.
     */
    public function index()
    {
        $query = BankTransaction::with('bankAccount.bank');
        $query->latest('transaction_date')->latest('id');
        $transactions = $query->paginate(20);
        return view('dashboard.bank-transactions.index', compact('transactions'));
    }

    /**
     * عرض نموذج إضافة حركة جديدة.
     */
    public function create()
    {
        $bankAccounts = BankAccount::with('bank')->where('is_active', true)->get();
        if ($bankAccounts->isEmpty()) {
            return redirect()->route('dashboard.bank-accounts.index')
                ->with('error', 'يجب إضافة حساب بنكي واحد على الأقل قبل تسجيل حركة.');
        }
        return view('dashboard.bank-transactions.create', compact('bankAccounts'));
    }

    /**
     * تخزين حركة جديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $request->validate(['type' => 'required|in:deposit,withdrawal,transfer']);

        try {
            DB::transaction(function () use ($request) {
                $type = $request->input('type');
                if ($type === 'deposit' || $type === 'withdrawal') {
                    $this->handleSingleTransaction($request, $type);
                } elseif ($type === 'transfer') {
                    $this->handleTransferTransaction($request);
                }
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'خطأ: ' . $e->getMessage());
        }

        return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم تسجيل الحركة بنجاح.');
    }

    /**
     * عرض نموذج تعديل حركة موجودة.
     */
    public function edit(BankTransaction $bankTransaction)
    {
        // لا يمكن تعديل الحوالات لأنها تؤثر على حسابين، فقط الإيداع والسحب
        if (in_array($bankTransaction->type, ['transfer_in', 'transfer_out'])) {
            return redirect()->route('dashboard.bank-transactions.index')
                ->with('error', 'لا يمكن تعديل حركات الحوالات مباشرة. يجب حذفها وإعادة إنشائها.');
        }

        $bankAccounts = BankAccount::with('bank')->where('is_active', true)->get();

        // إعادة تسمية المتغير ليتوافق مع النموذج الموحد
        $transaction = $bankTransaction;

        return view('dashboard.bank-transactions.edit', compact('transaction', 'bankAccounts'));
    }

    /**
     * تحديث حركة موجودة في قاعدة البيانات.
     * هذا منطق معقد لأنه يتطلب التراجع عن التأثير القديم وتطبيق التأثير الجديد.
     */
    public function update(Request $request, BankTransaction $bankTransaction)
    {
        $validated = $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'transaction_date' => 'required|date',
            'type' => ['required', Rule::in(['deposit', 'withdrawal'])],
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string',
            'details' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $bankTransaction, $validated) {
                // 1. التراجع عن تأثير الحركة القديمة على الرصيد
                $oldAccount = BankAccount::find($bankTransaction->bank_account_id);
                if ($bankTransaction->type == 'deposit') {
                    $oldAccount->decrement('current_balance', $bankTransaction->amount);
                } else { // withdrawal
                    $oldAccount->increment('current_balance', $bankTransaction->amount);
                }

                // 2. تحديث بيانات الحركة نفسها
                $bankTransaction->update($validated);

                // 3. تطبيق التأثير الجديد للحركة المحدثة على الرصيد
                $newAccount = BankAccount::find($validated['bank_account_id']);
                if ($validated['type'] == 'deposit') {
                    $newAccount->increment('current_balance', $validated['amount']);
                } else { // withdrawal
                    if ($newAccount->current_balance < $validated['amount']) {
                         throw new \Exception('الرصيد في الحساب الجديد غير كافٍ لإتمام عملية التعديل.');
                    }
                    $newAccount->decrement('current_balance', $validated['amount']);
                }
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'خطأ: ' . $e->getMessage());
        }

        return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم تعديل الحركة بنجاح.');
    }

    /**
     * حذف حركة من قاعدة البيانات مع التراجع عن تأثيرها على الرصيد.
     */
    public function destroy(BankTransaction $bankTransaction)
    {
        // لا يمكن حذف حركة واحدة من حوالة، يجب حذف الحوالة كاملة (لم يتم تنفيذ هذا المنطق بعد)
        if (in_array($bankTransaction->type, ['transfer_in', 'transfer_out'])) {
            return back()->with('error', 'لا يمكن حذف حركة واحدة من حوالة. يجب حذف الحوالة كاملة.');
        }

        try {
            DB::transaction(function () use ($bankTransaction) {
                $account = $bankTransaction->bankAccount;

                // التراجع عن تأثير الحركة على الرصيد
                if ($bankTransaction->type == 'deposit') {
                    if ($account->current_balance < $bankTransaction->amount) {
                        throw new \Exception('لا يمكن حذف الحركة لأن رصيد الحساب لا يكفي للتراجع عن قيمتها.');
                    }
                    $account->decrement('current_balance', $bankTransaction->amount);
                } else { // withdrawal
                    $account->increment('current_balance', $bankTransaction->amount);
                }

                // حذف الحركة نفسها
                $bankTransaction->delete();
            });
        } catch (\Exception $e) {
            return back()->with('error', 'خطأ: ' . $e->getMessage());
        }

        return redirect()->route('dashboard.bank-transactions.index')->with('success', 'تم حذف الحركة وتحديث الرصيد بنجاح.');
    }

    // ===================================================================
    // الدوال المساعدة لتنظيم الكود (Helper Functions)
    // ===================================================================

    private function handleSingleTransaction(Request $request, string $type)
    {
        $validated = $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string',
            'details' => 'nullable|string',
        ]);

        $account = BankAccount::findOrFail($validated['bank_account_id']);
        $account->transactions()->create($validated + ['type' => $type]);

        if ($type === 'deposit') {
            $account->increment('current_balance', $validated['amount']);
        } else {
            if ($account->current_balance < $validated['amount']) {
                throw new \Exception('الرصيد في حساب السحب غير كافٍ لإتمام العملية.');
            }
            $account->decrement('current_balance', $validated['amount']);
        }
    }

    private function handleTransferTransaction(Request $request)
    {
        $validated = $request->validate([
            'from_account_id' => 'required|exists:bank_accounts,id',
            'to_account_id' => 'required|exists:bank_accounts,id|different:from_account_id',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string',
            'details' => 'nullable|string',
        ], ['to_account_id.different' => 'حساب المرسل والمستقبل يجب أن يكونا مختلفين.']);

        $fromAccount = BankAccount::findOrFail($validated['from_account_id']);
        $toAccount = BankAccount::findOrFail($validated['to_account_id']);

        if ($fromAccount->current_balance < $validated['amount']) {
            throw new \Exception('الرصيد في حساب المرسل غير كافٍ لإتمام الحوالة.');
        }

        // يمكنك إضافة تحقق من تطابق العملات هنا إذا أردت
        // if ($fromAccount->currency !== $toAccount->currency) {
        //     throw new \Exception('لا يمكن التحويل بين حسابات بعملات مختلفة.');
        // }

        // الحركة الأولى: سحب من حساب المرسل
        $fromAccount->transactions()->create([
            'transaction_date' => $validated['transaction_date'],
            'type' => 'transfer_out',
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'details' => 'حوالة إلى: ' . $toAccount->account_name . ' | ' . $validated['details'],
        ]);
        $fromAccount->decrement('current_balance', $validated['amount']);

        // الحركة الثانية: إيداع في حساب المستقبل
        $toAccount->transactions()->create([
            'transaction_date' => $validated['transaction_date'],
            'type' => 'transfer_in',
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'details' => 'حوالة من: ' . $fromAccount->account_name . ' | ' . $validated['details'],
        ]);
        $toAccount->increment('current_balance', $validated['amount']);
    }
public function trash()
    {
        $transactions = BankTransaction::onlyTrashed()
            ->with('bankAccount.bank')
            ->latest('deleted_at')
            ->paginate(15);

        return view('dashboard.bank-transactions.trash', compact('transactions'));
    }

    /**
     * استعادة حركة من سلة المحذوفات.
     */
    public function restore($id)
    {
        $transaction = BankTransaction::onlyTrashed()->findOrFail($id);

        // لا تقم بتحديث الرصيد عند الاستعادة لتجنب الحسابات المزدوجة
        // يجب على المستخدم مراجعة الرصيد يدويًا أو حذف الحركة نهائيًا
        $transaction->restore();

        return back()->with('success', 'تم استعادة الحركة بنجاح. يرجى مراجعة رصيد الحساب يدويًا.');
    }

    /**
     * حذف حركة نهائيًا من قاعدة البيانات.
     */
    public function forceDelete($id)
    {
        $transaction = BankTransaction::onlyTrashed()->findOrFail($id);

        $transaction->forceDelete();

        return back()->with('success', 'تم حذف الحركة نهائيًا.');
    }}
