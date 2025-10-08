<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Exports\ProjectsExport; 
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('owner_name', 'LIKE', "%{$search}%")
                  ->orWhere('project_title', 'LIKE', "%{$search}%");
        }

        $projects = $query->orderBy($sortBy, $sortOrder)->paginate(10);

        return view('dashboard.projects.index', compact('projects', 'search', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        return view('dashboard.projects.create');
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

    public function edit(Project $project)
    {
        return view('dashboard.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $this->validateProject($request, $project->id);

        if ($request->hasFile('project_media')) {
            // حذف الملف القديم إذا وجد
            if ($project->project_media) {
                Storage::disk('public')->delete($project->project_media);
            }
            $validated['project_media'] = $request->file('project_media')->store('projects', 'public');
        }

        $project->update($validated);
        return redirect()->route('dashboard.projects.index')->with('success', 'تم تحديث المشروع بنجاح.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return back()->with('success', 'تم نقل المشروع إلى سلة المحذوفات.');
    }

    public function trash()
    {
        $trashedProjects = \App\Models\Project::onlyTrashed()->paginate(10);
        return view('dashboard.projects.trash', ['projects' => $trashedProjects]);
    }

    public function restore($id)
    {
        Project::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'تم استعادة المشروع بنجاح.');
    }

    public function forceDelete($id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        if ($project->project_media) {
            Storage::disk('public')->delete($project->project_media);
        }
        $project->forceDelete();
        return back()->with('success', 'تم حذف المشروع نهائياً.');
    }

    public function exportExcel()
    {
        return Excel::download(new ProjectsExport, 'projects.xlsx');
    }

    private function validateProject(Request $request, $projectId = null)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_phone' => ['required', 'string', 'max:50'],
            'owner_id' => ['required', 'string', 'max:50'],
            'project_title' => ['required', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'max:10'],
            'apartment_price' => ['nullable', 'numeric'],
            'down_payment' => ['nullable', 'numeric'],
            'project_status' => ['nullable', 'string', 'max:50'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'cash_receiver' => ['nullable', 'string', 'max:100'],
            'cash_receiver_other' => ['nullable', 'string', 'max:100'],
            'cash_receiver_job' => ['nullable', 'string', 'max:100'],
            'sender_bank' => ['nullable', 'string', 'max:100'],
            'sender_bank_other' => ['nullable', 'string', 'max:100'],
            'sender_branch' => ['nullable', 'string', 'max:100'],
            'receiver_bank' => ['nullable', 'string', 'max:100'],
            'receiver_bank_other' => ['nullable', 'string', 'max:100'],
            'receiver_branch' => ['nullable', 'string', 'max:100'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'check_number' => ['nullable', 'string', 'max:100'],
            'check_owner' => ['nullable', 'string', 'max:100'],
            'check_holder' => ['nullable', 'string', 'max:100'],
            'check_due_date' => ['nullable', 'date'],
            'check_receive_date' => ['nullable', 'date'],
            'project_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,mp4', 'max:20480'],
            'land_cost' => ['nullable', 'numeric'],
            'excavation_cost' => ['nullable', 'numeric'],
            'engineers_cost' => ['nullable', 'numeric'],
            'licensing_cost' => ['nullable', 'numeric'],
            'materials_cost' => ['nullable', 'numeric'],
            'finishing_cost' => ['nullable', 'numeric'],
            'total_budget' => ['nullable', 'numeric'],
        ];

        return $request->validate($rules);
    }
}
