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
use App\Exports\ExpensesExport;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('project')->latest();
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(fn($q) => $q->where('payee', 'LIKE', "%{$searchTerm}%")
                ->orWhere('details', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('project', fn($pq) => $pq->where('project_name', 'LIKE', "%{$searchTerm}%")));
        }
        $expenses = $query->paginate(15);
        return view('dashboard.expenses.index', ['expenses' => $expenses, 'search' => $request->search ?? '']);
    }

    public function create()
    {
        $projects = Project::all();
        $bankAccounts = BankAccount::where('is_active', true)->get();
        return view('dashboard.expenses.create', compact('projects', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateExpense($request);
        $validated['user_id'] = Auth::id();
        $validated['amount_ils'] = ($validated['currency'] === 'ILS' || $validated['currency'] === 'شيكل') ? $validated['amount'] : ($validated['amount'] * ($validated['exchange_rate'] ?? 1));

        DB::beginTransaction();
        try {
            $expense = Expense::create($validated);

            if ($expense->payment_source === 'خزينة') {
                CashTransaction::create([
                    'type' => 'out', 'transaction_date' => $expense->date,
                    'amount' => $expense->amount_ils, 'amount_ils' => $expense->amount_ils,
                    'source' => 'مصروف رقم ' . $expense->id . ': ' . $expense->payee,
                    'details' => $expense->details, 'voucher_id' => $expense->id,
                ]);
            } elseif ($expense->payment_source === 'بنك' && $request->filled('sender_bank_account_id')) {
                $bankAccount = BankAccount::findOrFail($request->sender_bank_account_id);
                BankTransaction::create([
                    'type' => 'withdrawal', 'bank_account_id' => $bankAccount->id,
                    'transaction_date' => $expense->date, 'amount' => $expense->amount,
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

    public function show(Expense $expense)
    {
        return view('dashboard.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $projects = Project::all();
        $bankAccounts = BankAccount::where('is_active', true)->get();
        return view('dashboard.expenses.edit', compact('expense', 'projects', 'bankAccounts'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $this->validateExpense($request);
        $validated['amount_ils'] = ($validated['currency'] === 'ILS' || $validated['currency'] === 'شيكل') ? $validated['amount'] : ($validated['amount'] * ($validated['exchange_rate'] ?? 1));
        $expense->update($validated);
        return redirect()->route('dashboard.expenses.index')->with('success', 'تم تحديث المصروف بنجاح!');
    }

    public function destroy(Expense $expense) { $expense->delete(); return back()->with('success', 'تم نقل المصروف إلى سلة المحذوفات!'); }
    public function trash() { $trashed = Expense::onlyTrashed()->latest()->paginate(15); return view('dashboard.expenses.trash', ['expenses' => $trashed]); }
    public function restore($id) { Expense::withTrashed()->findOrFail($id)->restore(); return back()->with('success', 'تم استعادة المصروف!'); }
    public function forceDelete($id) { Expense::withTrashed()->findOrFail($id)->forceDelete(); return back()->with('success', 'تم حذف المصروف نهائياً!'); }
    public function exportExcel() { return Excel::download(new ExpensesExport, 'expenses.xlsx'); }

    private function validateExpense(Request $request)
    {
        $rules = [
            'date' => ['required', 'date'], 'receipt_name' => ['nullable', 'string', 'max:255'],
            'receipt_value_shekel' => ['nullable', 'numeric'], 'cost_value_dollar' => ['nullable', 'numeric'],
            'payee' => ['required', 'string', 'max:255'], 'phone' => ['nullable', 'string', 'max:20'],
            'job' => ['nullable', 'string', 'max:100'], 'id_number' => ['nullable', 'string', 'max:50'],
            'walid_share_amount' => ['nullable', 'numeric'], 'mohammad_khalid_share_amount' => ['nullable', 'numeric'],
            'project_id' => ['required', 'integer'], 'amount' => ['required', 'numeric', 'min:0'],
            'walid_paid_dollar' => ['nullable', 'numeric'], 'mohammad_khalid_paid_dollar' => ['nullable', 'numeric'],
            'walid_paid_shekel' => ['nullable', 'numeric'], 'mohammad_khalid_paid_shekel' => ['nullable', 'numeric'],
            'remaining_amount' => ['nullable', 'numeric'], 'remaining_amount_dollar' => ['nullable', 'numeric'],
            'difference_in_payments' => ['nullable', 'numeric'], 'total_paid_amount' => ['nullable', 'numeric'],
            'currency' => ['required', 'string', 'max:10'], 'exchange_rate' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'], 'payment_source' => ['required', 'string', 'max:50'],
            'sender_bank_account_id' => ['required_if:payment_source,بنك', 'nullable', 'exists:bank_accounts,id'],
            'details' => ['nullable', 'string', 'max:5000'], 'notes' => ['nullable', 'string', 'max:5000'],
            'payment_details' => ['nullable', 'array']
        ];
        return $request->validate($rules);
    }
}
