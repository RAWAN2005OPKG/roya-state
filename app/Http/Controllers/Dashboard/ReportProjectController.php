<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectsExport;
use Illuminate\Support\Facades\Storage;

class ReportProjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'name');
        $sortOrder = $request->query('sort_order', 'asc');

        $query = Project::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('project_title', 'like', "%{$search}%");
        }

        $query->orderBy($sortBy, $sortOrder);
        $projects = $query->paginate(10);

        return view('dashboard.reportproject.index', compact('projects', 'search', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        // نمرر متغير project فارغ لتجنب الأخطاء في الفورم المشترك
        $project = new Project();
        return view('dashboard.reportproject.create', compact('project'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_title' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'project_status' => 'required|in:قيد التنفيذ,مكتمل,معلق,ملغى',
            'total_budget' => 'required|numeric|min:0',
            'currency' => 'required|in:ر.س,USD,EUR',
            'description' => 'nullable|string',
            'additional_info' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        if ($request->hasFile('files')) {
            $files = [];
            foreach ($request->file('files') as $file) {
                $files[] = $file->store('project_files', 'public');
            }
            $validated['files'] = json_encode($files);
        }

        Project::create($validated);

        return redirect()->route('dashboard.reportproject.index')->with('success', 'تم إنشاء المشروع بنجاح.');
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        return view('dashboard.reportproject.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('dashboard.reportproject.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_title' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'project_status' => 'required|in:قيد التنفيذ,مكتمل,معلق,ملغى',
            'total_budget' => 'required|numeric|min:0',
            'currency' => 'required|in:ر.س,USD,EUR',
            'description' => 'nullable|string',
            'additional_info' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        $files = json_decode($project->files ?? '[]', true);
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $files[] = $file->store('project_files', 'public');
            }
        }
        $validated['files'] = json_encode($files);

        $project->update($validated);

        return redirect()->route('dashboard.reportproject.index')->with('success', 'تم تحديث المشروع بنجاح.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        // تم التعديل ليتوافق مع SweetAlert
        return redirect()->route('dashboard.reportproject.index')->with('success', 'تم نقل المشروع إلى سلة المحذوفات بنجاح.');
    }

    public function trash()
    {
        $projects = Project::onlyTrashed()->paginate(10);
        return view('dashboard.reportproject.trash', compact('projects'));
    }

    public function restore($id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->restore();

        return redirect()->route('dashboard.reportproject.trash.index')->with('success', 'تم استعادة المشروع بنجاح.');
    }

    public function forceDelete($id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);

        if ($project->files) {
            $files = json_decode($project->files, true);
            foreach ($files as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $project->forceDelete();

        return redirect()->route('dashboard.reportproject.trash.index')->with('success', 'تم حذف المشروع نهائيًا.');
    }

    public function exportExcel()
    {
        return Excel::download(new ProjectsExport, 'projects.xlsx');
    }
}
