<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Exports\ExpensesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    /**
     * عرض قائمة المصاريف مع خاصية البحث.
     */
    public function index(Request $request)
    {
        // Eager load 'project' relationship for better performance
        $query = Expense::with('project')->latest();

        // التعامل مع خاصية البحث
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('payee', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('notes', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('payment_method', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('transaction_id', 'LIKE', "%{$searchTerm}%")
                  // البحث في أسماء المشاريع المرتبطة
                  ->orWhereHas('project', function ($projectQuery) use ($searchTerm) {
                      // **ملاحظة هامة:** يجب استبدال 'name' بالاسم الصحيح لعمود اسم المشروع لديك
                      // مثال: 'project_name', 'title'
                      $projectQuery->where('name', 'LIKE', "%{$searchTerm}%");
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
        // جلب كل المشاريع. هذا يحل مشكلة "Column not found".
        $projects = Project::all();
        return view('dashboard.expenses.create', compact('projects'));
    }

    /**
     * تخزين مصروف جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validated = $this->validateExpense($request);

        // تحويل 'project_id' من 0 (مصروف عام) إلى null للتخزين في قاعدة البيانات
        if ($validated['project_id'] == 0) {
            $validated['project_id'] = null;
        }

        Expense::create($validated);

        return redirect()->route('dashboard.expenses.index')->with('success', 'تم حفظ المصروف بنجاح.');
    }

    /**
     * عرض نموذج تعديل مصروف محدد.
     */
    public function edit(Expense $expense)
    {
        // جلب كل المشاريع.
        $projects = Project::all();
        return view('dashboard.expenses.edit', compact('expense', 'projects'));
    }

    /**
     * تحديث مصروف محدد في قاعدة البيانات.
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $this->validateExpense($request);

        // تحويل 'project_id' من 0 (مصروف عام) إلى null للتخزين
        if ($validated['project_id'] == 0) {
            $validated['project_id'] = null;
        }

        $expense->update($validated);

        return redirect()->route('dashboard.expenses.index')->with('success', 'تم تحديث المصروف بنجاح!');
    }

    /**
     * حذف مصروف (حذف ناعم).
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'تم نقل المصروف إلى سلة المحذوفات!');
    }

    /**
     * عرض المصاريف المحذوفة.
     */
    public function trash()
    {
        $trashedExpenses = Expense::onlyTrashed()->latest('deleted_at')->get();
        return view('dashboard.expenses.trash', ['expenses' => $trashedExpenses]);
    }

    /**
     * استعادة مصروف محذوف.
     */
    public function restore($id)
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $expense->restore();
        return back()->with('success', 'تم استعادة المصروف بنجاح!');
    }

    /**
     * حذف مصروف بشكل نهائي.
     */
    public function forceDelete($id)
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $expense->forceDelete();
        return back()->with('success', 'تم حذف المصروف نهائياً!');
    }

    /**
     * تصدير المصاريف إلى ملف Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new ExpensesExport, 'expenses.xlsx');
    }

    /**
     * دالة خاصة للتحقق من صحة البيانات (تُستخدم في store و update).
     */
    private function validateExpense(Request $request)
    {
        return $request->validate([
            'date' => ['required', 'date'],
            'payee' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'job' => ['nullable', 'string', 'max:100'],
            'id_number' => ['nullable', 'string', 'max:50'],
            'project_id' => ['required', 'integer'], // يتحقق من أن القيمة رقم (0 يعتبر رقمًا صالحًا هنا)
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'payment_method' => ['required', 'string', 'max:50'],
            'payment_source' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            // الحقول الديناميكية
            'cash_receiver' => ['nullable', 'string', 'max:100'],
            'cash_receiver_other' => ['nullable', 'string', 'max:100'],
            'receiver_job' => ['nullable', 'string', 'max:100'],
            'sender_bank' => ['nullable', 'string', 'max:100'],
            'other_sender_bank' => ['nullable', 'string', 'max:100'],
            'sender_branch' => ['nullable', 'string', 'max:100'],
            'receiver_bank' => ['nullable', 'string', 'max:100'],
            'receiver_branch' => ['nullable', 'string', 'max:100'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'check_number' => ['nullable', 'string', 'max:100'],
            'check_owner' => ['nullable', 'string', 'max:100'],
            'check_holder' => ['nullable', 'string', 'max:100'],
            'check_due_date' => ['nullable', 'date'],
            'check_receive_date' => ['nullable', 'date'],
        ]);
    }
}
