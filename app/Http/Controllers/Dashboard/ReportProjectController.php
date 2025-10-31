<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // ** التصحيح الأول: استخدام المسار الصحيح لكلاس Request **
use App\Models\ReportProject; // ** التصحيح الثاني: استخدام المودل الصحيح الخاص بتقارير المشاريع **
use App\Exports\ReportProjectExport; // تأكدي من أن اسم ملف التصدير صحيح
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReportProjectController extends Controller
{
    /**
     * عرض قائمة تقارير المشاريع.
     */
    public function index(Request $request)
    {
        // ** التصحيح الثالث: البحث في مودل ReportProject **
        $query = ReportProject::query();
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($search) {
            // البحث في الحقول الخاصة بـ ReportProject
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('owner_name', 'LIKE', "%{$search}%")
                  ->orWhere('project_title', 'LIKE', "%{$search}%");
        }

        $projects = $query->orderBy($sortBy, $sortOrder)->paginate(10);

        return view('dashboard.reportproject.index', compact('projects', 'search', 'sortBy', 'sortOrder'));
    }

    /**
     * عرض نموذج إنشاء تقرير مشروع جديد.
     */
    public function create()
    {
        $project = new ReportProject(); // استخدام المودل الصحيح
        return view('dashboard.reportproject.create', compact('project'));
    }

    /**
     * تخزين تقرير مشروع جديد.
     */
    public function store(Request $request)
    {
        // استخدام دالة التحقق من الصحة بعد تعديلها
        $validated = $this->validateReportProject($request);

        if ($request->hasFile('project_media')) {
            $validated['project_media'] = $request->file('project_media')->store('report_projects', 'public');
        }

        ReportProject::create($validated); // استخدام المودل الصحيح

        return redirect()->route('dashboard.reportproject.index')->with('success', 'تم إضافة تقرير المشروع بنجاح.');
    }

    /**
     * عرض تفاصيل تقرير مشروع.
     */
    public function show(ReportProject $project) // استخدام الحقن للمودل الصحيح
    {
        // يمكنك إضافة أي علاقات تحتاجينها هنا
        // $project->load('some_relation');

        return view('dashboard.reportproject.show', compact('project'));
    }

    /**
     * عرض نموذج تعديل تقرير مشروع.
     */
    public function edit(ReportProject $project) // استخدام الحقن للمودل الصحيح
    {
        return view('dashboard.reportproject.edit', compact('project'));
    }

    /**
     * تحديث بيانات تقرير مشروع.
     */
    public function update(Request $request, ReportProject $project) // استخدام الحقن للمودل الصحيح
    {
        $validated = $this->validateReportProject($request, $project->id);

        if ($request->hasFile('project_media')) {
            if ($project->project_media) {
                Storage::disk('public')->delete($project->project_media);
            }
            $validated['project_media'] = $request->file('project_media')->store('report_projects', 'public');
        }

        $project->update($validated);

        return redirect()->route('dashboard.reportproject.index')->with('success', 'تم تحديث تقرير المشروع بنجاح.');
    }

    /**
     * نقل تقرير المشروع إلى سلة المحذوفات.
     */
    public function destroy(ReportProject $project) // استخدام الحقن للمودل الصحيح
    {
        $project->delete();
        return back()->with('success', 'تم نقل تقرير المشروع إلى سلة المحذوفات.');
    }

    // --- دوال سلة المحذوفات ---
    public function trash()
    {
        $trashedProjects = ReportProject::onlyTrashed()->paginate(10);
        return view('dashboard.reportproject.trash', ['projects' => $trashedProjects]);
    }

    public function restore($id)
    {
        ReportProject::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'تم استعادة تقرير المشروع بنجاح.');
    }

    public function forceDelete($id)
    {
        $project = ReportProject::withTrashed()->findOrFail($id);
        if ($project->project_media) {
            Storage::disk('public')->delete($project->project_media);
        }
        $project->forceDelete();
        return back()->with('success', 'تم حذف تقرير المشروع نهائيًا.');
    }

    // --- دالة التصدير ---
    public function exportExcel()
    {
        return Excel::download(new ReportProjectExport, 'report_projects.xlsx');
    }

    /**
     * ** التصحيح الرابع: دالة تحقق خاصة بـ ReportProject **
     * تم تبسيط الحقول لتكون منطقية لتقرير مشروع.
     */
    private function validateReportProject(Request $request, $projectId = null)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'project_title' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_phone' => ['nullable', 'string', 'max:50'],
            'owner_id' => ['nullable', 'string', 'max:50'],
            'start_date' => ['nullable', 'date'],
            'project_status' => ['nullable', 'string', 'max:50'],
            'total_budget' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
            'project_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,mp4', 'max:20480'],
            // أزلت الحقول غير المنطقية مثل تفاصيل الدفع والشيكات
            // هذه الحقول يجب أن تكون في جداول أخرى (مثل جدول الدفعات)
        ];

        return $request->validate($rules);
    }
}
