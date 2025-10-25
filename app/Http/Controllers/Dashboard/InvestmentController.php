<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Investor;
use App\Models\Project;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::with(['investor', 'project'])->get();
        return view('dashboard.investments.index', compact('investments'));
    }

  // صفحة إنشاء استثمار جديد
public function create()
{
    $investors = Investor::all(); // جميع المستثمرين
    $projects = Project::all();   // جميع المشاريع

    return view('dashboard.investments.create', compact('investors', 'projects'));
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'project_id' => 'required|exists:projects,id',
            'investment_date' => 'nullable|date',
            'investment_type' => 'nullable|string|max:255',
            'currency' => 'required|in:usd,ils,jod',
            'amount' => 'required|numeric|min:1',
            'share_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,draft',
        ]);

        Investment::create($validated);

        return redirect()->route('dashboard.investments.index')->with('success', 'تمت إضافة الاستثمار بنجاح');
    }

    public function edit(Investment $investment)
    {
        $projects = Project::all();
        $investors = Investor::all();
        return view('dashboard.investments.edit', compact('investment', 'project', 'investors'));
    }

      public function update(Request $request, Investment $investment)
    {
        $validated = $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'project_id' => 'required|exists:projects,id',
            'investment_date' => 'nullable|date',
            'investment_type' => 'nullable|string|max:255',
            'currency' => 'required|in:usd,ils,jod',
            'amount' => 'required|numeric|min:1',
            'share_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,draft',
        ]);

        $investment->update($validated);

        return redirect()->route('dashboard.investments.index')->with('success', 'تم تحديث بيانات الاستثمار بنجاح');
    }

    public function destroy(Investment $investment)
    {
        $investment->delete();
        return redirect()->route('dashboard.investments.index')->with('success', 'تم حذف الاستثمار بنجاح');
    }
}
