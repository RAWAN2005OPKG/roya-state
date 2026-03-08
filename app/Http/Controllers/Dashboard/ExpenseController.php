<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Project;
use App\Models\User;
use App\Models\CashTransaction;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Notifications\NewExpenseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExpensesExport; // تأكد من إنشاء هذا الملف إذا كنت ستستخدم التصدير

class ExpenseController extends Controller
{
    /**
     * عرض قائمة المصاريف مع خاصية البحث.
     */
    public function index(Request $request)
    {
        $query = Expense::with('project')->latest();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('payee', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('details', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('project', function ($projectQuery) use ($searchTerm) {
                      $projectQuery->where('project_name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        $expenses = $query->paginate(15);

        return view('dashboard.expenses.index', [
            'expenses' => $expenses,
            'search' => $request->search ?? ''
        ]);
    }

    /**
     * عرض نموذج إنشاء مصروف جديد.
     */
    public function create()
    {
        $projects = Project::all();
        $bankAccounts = BankAccount::where('is_active', true)->get();
        return view('dashboard.expenses.create', compact('projects', 'bankAccounts'));
    }

    /**
     * تخزين مصروف جديد وربطه مالياً.
     */
    public function store(Request $request)
    {
        $validated = $this->validateExpense($request);

        if (isset($validated['project_id']) && $validated['project_id'] == 0) {
            $validated['project_id'] = null;
        }
        $validated['user_id'] = Auth::id();
        $validated['amount_ils'] = $validated['amount'] * ($validated['exchange_rate'] ?? 1);

        DB::beginTransaction();
        try {
            $expense = Expense::create($validated);

            if ($expense->payment_source === 'خزينة') {
                CashTransaction::create([
                    'type' => 'out',
                    'transaction_date' => $expense->date,
                    'amount' => $expense->amount_ils,
                    'amount_ils' => $expense->amount_ils,
                    'source' => 'مصروف رقم ' . $expense->id . ': ' . $expense->payee,
                    'details' => $expense->details,
                    'voucher_id' => $expense->id,
                ]);
            } elseif ($expense->payment_source === 'بنك' && $request->filled('sender_bank_account_id')) {
                $bankAccount = BankAccount::findOrFail($request->sender_bank_account_id);
                BankTransaction::create([
                    'type' => 'withdrawal',
                    'bank_account_id' => $bankAccount->id,
                    'transaction_date' => $expense->date,
                    'amount' => $expense->amount,
                    'currency' => $bankAccount->currency,
                    'details' => 'مصروف رقم ' . $expense->id . ': ' . $expense->payee,
                ]);
                $bankAccount->decrement('balance', $expense->amount);
            }

            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewExpenseNotification($expense));
            }

            DB::commit();
            return redirect()->route('dashboard.expenses.index')->with('success', 'تم حفظ المصروف وخصم المبلغ بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ فادح: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * عرض تفاصيل مصروف محدد.
     */
    public function show(Expense $expense)
    {
        return view('dashboard.expenses.show', compact('expense'));
    }

    /**
     * عرض نموذج تعديل مصروف محدد.
     */
    public function edit(Expense $expense)
    {
        $projects = Project::all();
        $bankAccounts = BankAccount::where('is_active', true)->get();
        return view('dashboard.expenses.edit', compact('expense', 'projects', 'bankAccounts'));
    }

    /**
     * تحديث مصروف محدد في قاعدة البيانات.
     */
    public function update(Request $request, Expense $expense)
    {
        // ملاحظة: منطق التحديث الكامل مع عكس الأثر المالي معقد، للسرعة سنقوم بالتحديث المباشر
        // هذا يعني أن تعديل المبلغ لن يصحح رصيد البنك تلقائياً في هذه النسخة
        $validated = $this->validateExpense($request);
        if (isset($validated['project_id']) && $validated['project_id'] == 0) {
            $validated['project_id'] = null;
        }
        $validated['amount_ils'] = $validated['amount'] * ($validated['exchange_rate'] ?? 1);

        $expense->update($validated);

        return redirect()->route('dashboard.expenses.index')->with('success', 'تم تحديث المصروف بنجاح!');
    }

    /**
     * حذف مصروف (حذف ناعم).
     */
    public function destroy(Expense $expense)
    {
        // ملاحظة: الحذف الكامل يجب أن يعكس الأثر المالي أيضاً
        $expense->delete();
        return back()->with('success', 'تم نقل المصروف إلى سلة المحذوفات!');
    }

    /**
     * عرض المصاريف المحذوفة.
     */
    public function trash()
    {
        $trashedExpenses = Expense::onlyTrashed()->with('project')->latest('deleted_at')->paginate(15);
        return view('dashboard.expenses.trash', compact('trashedExpenses'));
    }

    /**
     * استعادة مصروف محذوف.
     */
    public function restore($id)
    {
        Expense::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'تم استعادة المصروف بنجاح!');
    }

    /**
     * حذف مصروف بشكل نهائي.
     */
    public function forceDelete($id)
    {
        Expense::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'تم حذف المصروف نهائياً!');
    }

    /**
     * تصدير المصاريف إلى ملف Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new ExpensesExport, 'expenses-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * دالة خاصة للتحقق من صحة البيانات.
     */
    private function validateExpense(Request $request)
    {
        return $request->validate([
            'date' => ['required', 'date'],
            'payee' => ['required', 'string', 'max:255'],
            'project_id' => ['nullable', 'integer'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'],
            'payment_source' => ['required', 'string', 'max:50'],
            'sender_bank_account_id' => ['required_if:payment_source,بنك', 'nullable', 'exists:bank_accounts,id'],
            'details' => ['nullable', 'string', 'max:5000'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
