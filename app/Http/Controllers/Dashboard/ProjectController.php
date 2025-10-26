<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectsExport;

class ProjectController extends Controller
{
    //  عرض جميع المشاريع
    public function index(Request $request)
    {
        // ** تعديل بسيط: إضافة علاقة العملاء للبحث **
        $query = Project::with(['investments.investor', 'customers']);
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($search) {
            $query->where('project_name', 'LIKE', "%{$search}%")
                  ->orWhere('project_title', 'LIKE', "%{$search}%")
                  ->orWhereHas('investments.investor', function ($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  })
                  // ** الإضافة الجديدة: البحث في أسماء العملاء المرتبطين **
                  ->orWhereHas('customers', function ($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
        }

        $projects = $query->orderBy($sortBy, $sortOrder)->paginate(10);

        return view('dashboard.projects.index', compact('projects', 'search', 'sortBy', 'sortOrder'));
    }

    //  صفحة إنشاء مشروع جديد
    public function create()
    {
        $project = new Project();
        return view('dashboard.projects.create', compact('project'));
    }

    //  تخزين مشروع جديد
    public function store(Request $request)
    {
        $validated = $this->validateProject($request);

        if ($request->hasFile('project_media')) {
            $validated['project_media'] = $request->file('project_media')->store('projects', 'public');
        }

        Project::create($validated);

        return redirect()->route('dashboard.projects.index')->with('success', 'تم إضافة المشروع بنجاح.');
    }

    //  عرض تفاصيل المشروع
    public function show(Project $project)
    {
        // ** التعديل الأهم **
        // نقوم بتحميل العلاقات (الاستثمارات والمستثمرين، والعملاء) بكفاءة
        $project->load(['investments.investor', 'customers']);

        // لم نعد بحاجة إلى استدعاء totalInvested هنا لأننا سنستخدمه في الـ Blade مباشرة
        // $totalInvested = $project->totalInvested();

        // نرسل كائن المشروع فقط، وهو يحتوي على كل شيء
        return view('dashboard.projects.show', compact('project'));
    }

    //  تعديل مشروع
    public function edit(Project $project)
    {
        return view('dashboard.projects.edit', compact('project'));
    }

    //  تحديث بيانات المشروع
    public function update(Request $request, Project $project)
    {
        $validated = $this->validateProject($request);

        if ($request->hasFile('project_media')) {
            if ($project->project_media) {
                Storage::disk('public')->delete($project->project_media);
            }
            $validated['project_media'] = $request->file('project_media')->store('projects', 'public');
        }

        $project->update($validated);

        return redirect()->route('dashboard.projects.index')->with('success', 'تم تحديث المشروع بنجاح.');
    }

    //  نقل المشروع إلى سلة المحذوفات
    public function destroy(Project $project)
    {
        $project->delete();
        return back()->with('success', 'تم نقل المشروع إلى سلة المحذوفات.');
    }

    //  عرض المشاريع المحذوفة
    public function trash()
    {
        $trashedProjects = Project::onlyTrashed()->paginate(10);
        return view('dashboard.projects.trash', compact('trashedProjects'));
    }

    //  استعادة مشروع من السلة
    public function restore($id)
    {
        Project::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'تم استعادة المشروع بنجاح.');
    }

    //  حذف مشروع نهائيًا
    public function forceDelete($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        if ($project->project_media) {
            Storage::disk('public')->delete($project->project_media);
        }

        $project->forceDelete();
        return back()->with('success', 'تم حذف المشروع نهائيًا.');
    }

    public function exportExcel()
    {
        return Excel::download(new ProjectsExport, 'projects.xlsx');
    }

    private function validateProject(Request $request)
    {
        return $request->validate([
            'project_name' => ['required', 'string', 'max:255'],
            'project_title' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'currency' => ['nullable', 'string', 'max:10'],
            'apartment_price' => ['nullable', 'numeric'],
            'down_payment' => ['nullable', 'numeric'],
            'budget' => ['nullable', 'numeric'],
            'project_status' => ['nullable', 'string', 'max:50'],
            'project_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,mp4', 'max:20480'],
        ]);
    }
}
