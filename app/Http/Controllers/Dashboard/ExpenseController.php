<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Exports\ExpensesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{

    public function index()
    {
        $expenses = Expense::latest()->get();
        return view ("dashboard.expenses", ['expenses' => $expenses]);
    }


    public function create()
    {
        return view('dashboard.expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'            => ['required', 'date'],
            'payee'           => ['required', 'string', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:50'],
            'job'             => ['nullable', 'string', 'max:100'],
            'id_number'       => ['nullable', 'string', 'max:50'],
            'project_id'      => ['nullable', 'integer'],
            'amount'          => ['required', 'numeric', 'min:0'],
            'currency'        => ['required', 'string', 'max:10'],
            'payment_method'  => ['required', 'string', 'max:50'],
            'payment_source'  => ['required', 'string', 'max:50'],
            'cash_receiver'        => ['nullable', 'string', 'max:100'],
            'cash_receiver_other'  => ['nullable', 'string', 'max:100'],
            'receiver_job'         => ['nullable', 'string', 'max:100'],
            'sender_bank'          => ['nullable', 'string', 'max:100'],
            'other_sender_bank'    => ['nullable', 'string', 'max:100'],
            'sender_branch'        => ['nullable', 'string', 'max:100'],
            'receiver_bank'        => ['nullable', 'string', 'max:100'],
            'other_receiver_bank'  => ['nullable', 'string', 'max:100'],
            'receiver_branch'      => ['nullable', 'string', 'max:100'],
            'transaction_id'       => ['nullable', 'string', 'max:100'],
            'check_number'         => ['nullable', 'string', 'max:100'],
            'check_owner'          => ['nullable', 'string', 'max:100'],
            'check_holder'         => ['nullable', 'string', 'max:100'],
            'check_due_date'       => ['nullable', 'date'],
            'check_receive_date'   => ['nullable', 'date'],
            'notes'                => ['nullable', 'string'],
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'تم حفظ المصروف بنجاح');
    }

    public function edit(Expense $expense)
    {
        return view('dashboard.expenses.edit', ['expense' => $expense]);
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'date'            => ['required', 'date'],
            'payee'           => ['required', 'string', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:50'],
            'job'             => ['nullable', 'string', 'max:100'],
            'id_number'       => ['nullable', 'string', 'max:50'],
            'project_id'      => ['nullable', 'integer'],
            'amount'          => ['required', 'numeric', 'min:0'],
            'currency'        => ['required', 'string', 'max:10'],
            'payment_method'  => ['required', 'string', 'max:50'],
            'payment_source'  => ['required', 'string', 'max:50'],
            'cash_receiver'        => ['nullable', 'string', 'max:100'],
            'cash_receiver_other'  => ['nullable', 'string', 'max:100'],
            'receiver_job'         => ['nullable', 'string', 'max:100'],
            'sender_bank'          => ['nullable', 'string', 'max:100'],
            'other_sender_bank'    => ['nullable', 'string', 'max:100'],
            'sender_branch'        => ['nullable', 'string', 'max:100'],
            'receiver_bank'        => ['nullable', 'string', 'max:100'],
            'other_receiver_bank'  => ['nullable', 'string', 'max:100'],
            'receiver_branch'      => ['nullable', 'string', 'max:100'],
            'transaction_id'       => ['nullable', 'string', 'max:100'],
            'check_number'         => ['nullable', 'string', 'max:100'],
            'check_owner'          => ['nullable', 'string', 'max:100'],
            'check_holder'         => ['nullable', 'string', 'max:100'],
            'check_due_date'       => ['nullable', 'date'],
            'check_receive_date'   => ['nullable', 'date'],
            'notes'                => ['nullable', 'string'],
        ]);

        $expense->update($validated);

        return redirect()->route('dashboard.expenses.index')->with('success', 'تم تحديث المصروف بنجاح!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'تم نقل المصروف إلى سلة المحذوفات!');
    }

    public function trash()
    {
        $trashedExpenses = Expense::onlyTrashed()->latest('deleted_at')->get();
        return view('dashboard.expenses.trash', ['expenses' => $trashedExpenses]);
    }

    public function restore($id)
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $expense->restore();
        return back()->with('success', 'تم استعادة المصروف بنجاح!');
    }

    public function forceDelete($id)
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $expense->forceDelete();
        return back()->with('success', 'تم حذف المصروف نهائياً!');
    }
        public function exportExcel()
    {
        return Excel::download(new ExpensesExport, 'expenses.xlsx');
    }
}

