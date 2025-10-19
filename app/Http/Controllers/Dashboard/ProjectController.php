<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Investment;
use App\Models\Investor;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectsExport;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('investments.investor');
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($search) {
            $query->where('project_name', 'LIKE', "%{$search}%")
                  ->orWhere('project_title', 'LIKE', "%{$search}%")
                  ->orWhereHas('investments.investor', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
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
        $project->load('investments.investor');
        $totalInvested = $project->totalInvested();

        return view('dashboard.projects.show', compact('project', 'totalInvested'));
    }

    public function edit(Project $project)
    {
        return view('dashboard.projects.edit', compact('project'));
    }

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

    public function destroy(Project $project)
    {
        $project->delete();
        return back()->with('success', 'تم نقل المشروع إلى سلة المحذوفات.');
    }

    public function trash()
    {
        $trashedProjects = Project::onlyTrashed()->paginate(10);
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
            'due_date' => ['nullable', 'date'],
            'project_name' => ['required', 'string', 'max:255'],
            'project_title' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
            'apartment_price' => ['required', 'numeric'],
            'down_payment' => ['required', 'numeric'],
            'project_status' => ['required', 'string', 'max:50'],
            'project_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,mp4', 'max:20480'],
        ]);
    }
}
