<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Exports\ProjectsExport;
use Illuminate\Support\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Notifications\ProjectStatusChangedNotification;
use Illuminate\Support\Facades\Notification;
class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::withCount(['contracts', 'expenses']);
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($search) {
            $query->where('project_name', 'LIKE', "%{$search}%")
                  ->orWhere('project_title', 'LIKE', "%{$search}%");
        }

        $projects = $query->orderBy($sortBy, $sortOrder)->paginate(10);
        return view('dashboard.projects.index', compact('projects', 'search', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        $project = new Project();
        return view('dashboard.projects.create', compact('project'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProject($request);
        if ($request->hasFile('project_media')) {
            $validated['project_media'] = $request->file('project_media')->store('projects', 'public');
        }
        Project::create($validated);
        return redirect()->route('dashboard.projects.index')->with('success', 'تم إضافة المشروع بنجاح.');
    }


public function show(Project $project)
{
    // استخدام load() لجلب كل العلاقات بكفاءة بعد تحميل المشروع
    $project->load(
        'customers',
        'investors',
        'expenses',
        'khaleedMohamedTransactions'
    );

    // حساب الإجماليات المالية للمشروع
    $totalExpenses = $project->expenses->sum('amount');
    $totalKhaleedMohamed = $project->khaleedMohamedTransactions->sum(function($t) {
        return $t->amount_shekel ?: $t->amount_dollar; // يجب توحيد العملة هنا للجمع الدقيق
    });

    // >>== ملاحظة: يجب توحيد العملات للحصول على أرقام دقيقة ==<<
    $totalProjectCosts = $totalExpenses + $totalKhaleedMohamed;
    $remainingBudget = $project->budget - $totalProjectCosts;

    return view('dashboard.projects.show', compact(
        'project',
        'totalProjectCosts',
        'remainingBudget'
    ));
}


    public function edit(Project $project)
    {
        return view('dashboard.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $this->validateProject($request, $project->id);
        if ($request->hasFile('project_media')) {
            if ($project->project_media) Storage::disk('public')->delete($project->project_media);
            $validated['project_media'] = $request->file('project_media')->store('projects', 'public');
        }
       $oldStatus = $project->status;
        $newStatus = $validatedData['status'];

        $project->update($validatedData);

        // >>== إرسال إشعار فقط إذا تغيرت حالة المشروع ==<<
        if ($oldStatus !== $newStatus) {
            $projectManager = $project->manager;
            $admins = User::where('role', 'admin')->get();

            $recipients = $admins;
            if ($projectManager) {
                $recipients = $recipients->push($projectManager)->unique();
            }

            if ($recipients->isNotEmpty()) {
                Notification::send($recipients, new ProjectStatusChangedNotification($project, $newStatus));
            }
        } return redirect()->route('dashboard.projects.index')->with('success', 'تم تحديث المشروع بنجاح.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return back()->with('success', 'تم نقل المشروع إلى سلة المحذوفات.');
    }

    public function trash()
    {
        $trashedProjects = Project::onlyTrashed()->latest('deleted_at')->paginate(10);
        return view('dashboard.projects.trash', compact('trashedProjects'));
    }

    public function restore($id)
    {
        Project::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'تم استعادة المشروع بنجاح.');
    }

    public function forceDelete($id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        if ($project->project_media) Storage::disk('public')->delete($project->project_media);
        $project->forceDelete();
        return back()->with('success', 'تم حذف المشروع نهائيًا.');
    }

    public function exportExcel()
    {
        return Excel::download(new ProjectsExport, 'projects.xlsx');
    }

    private function validateProject(Request $request, $projectId = null)
    {
        $rules = [
            'project_name' => ['required', 'string', 'max:255'],
            'project_title' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'currency' => ['nullable', 'string', 'max:10'],
            'apartment_price' => ['nullable', 'numeric', 'min:0'],
            'down_payment' => ['nullable', 'numeric', 'min:0'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'project_status' => ['nullable', 'string', 'max:50'],
            'project_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,mp4', 'max:20480'],
        ];
        return $request->validate($rules);
    }
}
