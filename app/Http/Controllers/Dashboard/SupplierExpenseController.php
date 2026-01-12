<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SupplierPayment;
use App\Models\Subcontractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierExpenseController extends Controller
{
    /**
     * عرض قائمة مصروفات الموردين.
     */
    public function index(Request $request)
    {
        // جلب المصروفات التي ترتبط فقط بالموردين
        $expenses = Expense::where('payable_type', Subcontractor::class)
                            ->with('payable')
                            ->latest('expense_date')
                            ->paginate(20);

        return view('dashboard.supplier_expenses.index', compact('expenses'));
    }

    /**
     * عرض نموذج إضافة مصروف جديد لمورد.
     */
    public function create()
    {
        $subcontractors = Subcontractor::select('id', 'name', 'specialization')->get();
        return view('dashboard.supplier_expenses.create', compact('subcontractors'));
    }

    /**
     * تخزين مصروف جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'subcontractor_id' => 'required|exists:subcontractors,id',
            'source_of_funds' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Expense::create([
            'expense_date' => $validated['expense_date'],
            'amount' => $validated['amount'],
            'payable_type' => Subcontractor::class, // النوع ثابت دائماً
            'payable_id' => $validated['subcontractor_id'],
            'source_of_funds' => $validated['source_of_funds'],
            'notes' => $validated['notes'],
            'paid_by' => Auth::user()->name, // اسم المستخدم الحالي
        ]);

        return redirect()->route('dashboard.supplier_expenses.index')->with('success', 'تم تسجيل المصروف للمورد بنجاح.');
    }
}
