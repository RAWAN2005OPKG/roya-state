<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Subcontractor;
use App\Exports\SubcontractorsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SubcontractorController extends Controller
{
    /**
     * عرض قائمة المقاولين والموردين مع ميزة البحث.
     */
    public function index(Request $request)
    {
        $query = Subcontractor::query();
        $search = $request->input('search');

        // التحسين رقم 1: استخدام "Advanced Where Clauses" لعزل منطق البحث
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('service_type', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // withCount('contracts') يفترض وجود علاقة 'contracts' في موديل Subcontractor
        $subcontractors = $query->withCount('contracts')->latest()->paginate(15);

        return view('dashboard.subcontractors.index', compact('subcontractors', 'search'));
    }

    /**
     * عرض صفحة إضافة مقاول جديد.
     */
    public function create()
    {
        return view('dashboard.subcontractors.create');
    }

    /**
     * تخزين مقاول جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
        ]);

        Subcontractor::create($validated);

        return redirect()->route('dashboard.subcontractors.index')->with('success', 'تم إضافة المقاول بنجاح.');
    }

    /**
     * عرض صفحة تفاصيل المقاول مع عقوده.
     */
    public function show(Subcontractor $subcontractor)
    {
        // التحسين رقم 2: تحميل العلاقات بكفاءة
        $subcontractor->load('contracts.project');

        return view('dashboard.subcontractors.show', compact('subcontractor'));
    }

    /**
     * عرض صفحة تعديل بيانات المقاول.
     */
    public function edit(Subcontractor $subcontractor)
    {
        return view('dashboard.subcontractors.edit', compact('subcontractor'));
    }

    /**
     * تحديث بيانات المقاول في قاعدة البيانات.
     */
    public function update(Request $request, Subcontractor $subcontractor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
        ]);

        $subcontractor->update($validated);

        return redirect()->route('dashboard.subcontractors.index')->with('success', 'تم تحديث بيانات المقاول بنجاح.');
    }

    /**
     * نقل المقاول إلى سلة المحذوفات (حذف ناعم).
     */
    public function destroy(Subcontractor $subcontractor)
    {
        // التحسين رقم 3: التحقق من وجود عقود مرتبطة قبل الحذف
        if ($subcontractor->contracts()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن حذف مقاول لديه عقود مرتبطة.');
        }

        $subcontractor->delete();

        return redirect()->route('dashboard.subcontractors.index')->with('success', 'تم نقل المقاول إلى سلة المحذوفات.');
    }

    /**
     * عرض سلة المحذوفات للمقاولين.
     */
    public function trash()
    {
        $trashedSubcontractors = Subcontractor::onlyTrashed()->latest('deleted_at')->paginate(15);
        return view('dashboard.subcontractors.trash', ['subcontractors' => $trashedSubcontractors]);
    }

    /**
     * استعادة مقاول من سلة المحذوفات.
     */
    public function restore($id)
    {
        $subcontractor = Subcontractor::withTrashed()->findOrFail($id);
        $subcontractor->restore();

        return redirect()->back()->with('success', 'تم استعادة المقاول بنجاح.');
    }

    /**
     * حذف المقاول نهائياً من قاعدة البيانات.
     */
    public function forceDelete($id)
    {
        $subcontractor = Subcontractor::withTrashed()->findOrFail($id);

        // التحسين رقم 4: التأكد مرة أخرى من عدم وجود عقود قبل الحذف النهائي
        if ($subcontractor->contracts()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن حذف مقاول لديه عقود مرتبطة، حتى من سلة المحذوفات.');
        }

        $subcontractor->forceDelete();

        return redirect()->back()->with('success', 'تم حذف المقاول نهائياً.');
    }

    /**
     * تصدير بيانات المقاولين إلى ملف Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new SubcontractorsExport, 'subcontractors.xlsx');
    }
}
