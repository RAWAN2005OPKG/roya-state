<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvestorController extends Controller
{
    public function index()
    {
        $investors = Investor::orderBy('id', 'desc')->paginate(10);
        return view('dashboard.investors.index', compact('investors'));
    }

    public function create()
    {
        $projects = Project::all(); // جلب جميع المشاريع للاختيار المتعدد
        return view('dashboard.investors.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:investors,email',
            'company' => 'nullable|string|max:255',

            'investments' => 'nullable|array',
            'investments.*.project_id' => 'required|exists:projects,id',
            'investments.*.investment_percentage' => 'required|numeric|min:0.01|max:100',
            'investments.*.invested_amount' => 'required|numeric|min:0',
            'investments.*.currency' => 'required|in:USD,SAR,EUR',
        ]);

        try {
            DB::beginTransaction();

            // 1. إنشاء المستثمر
            $investor = Investor::create($request->only(['name', 'phone', 'email', 'company', 'notes']));

            // 2. ربط المشاريع
            if ($request->investments) {
                $investmentsData = [];
                foreach ($request->investments as $investment) {
                    $investmentsData[$investment['project_id']] = [
                        'investment_percentage' => $investment['investment_percentage'],
                        'invested_amount' => $investment['invested_amount'],
                        'notes' => $investment['notes'] ?? null,
                        // العملة غير موجودة في جدول الربط project_investor، لذا يجب تخزينها في مكان آخر أو إزالتها
                        // سنفترض أن العملة موحدة (USD) أو يتم تخزينها في حقل notes مؤقتاً
                    ];
                }
                $investor->projects()->attach($investmentsData);
            }

            DB::commit();

            return redirect()->route('dashboard.investors.index')
                ->with('success', 'تم إضافة المستثمر بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء حفظ المستثمر: ' . $e->getMessage());
        }
    }
}
