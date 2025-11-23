<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProjectTransfer;
use App\Models\Expense; // افترض وجود مودل للمصاريف
use App\Models\Project; // افترض وجود مودل للمشاريع
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectTransferController extends Controller
{
    public function index()
    {
        $transfers = ProjectTransfer::latest()->paginate(20);
        $projects = Project::all();
        return view('dashboard.transfers.projects.index', compact('transfers', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'expense_id' => 'required|exists:expenses,id',
            'to_project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:500',
        ]);

        $expense = Expense::findOrFail($request->expense_id);

        // تأكد من أن المبلغ المحول لا يتجاوز قيمة المصروف
        if ($request->amount > $expense->amount) {
            return back()->withErrors(['amount' => 'المبلغ المحول لا يمكن أن يكون أكبر من قيمة المصروف الأصلية.']);
        }

        DB::beginTransaction();
        try {
            // 1. إنشاء سجل التحويل
            ProjectTransfer::create([
                'date' => $request->date,
                'expense_id' => $expense->id,
                'from_project_id' => $expense->project_id,
                'to_project_id' => $request->to_project_id,
                'amount' => $request->amount,
                'reason' => $request->reason,
            ]);

            // 2. تحديث المصروف الأصلي (هنا يمكنك اختيار إما تعديل المصروف أو إنشاء قيد محاسبي)
            // أبسط طريقة هي تعديل المشروع المرتبط بالمصروف
            $expense->project_id = $request->to_project_id;
            $expense->save();

            DB::commit();
            return back()->with('success', 'تم تحويل المصروف إلى المشروع الجديد بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء عملية التحويل.');
        }
    }
}
