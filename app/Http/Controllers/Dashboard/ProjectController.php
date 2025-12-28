<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * عرض قائمة المشاريع.
     */
    public function index()
    {
        $projects = Project::orderBy('id', 'desc')->paginate(10);
        return view('dashboard.projects.index', compact('projects'));
    }

    /**
     * عرض نموذج إنشاء مشروع جديد.
     */
    public function create()
    {
        $investors = Investor::all(); // لجلب قائمة المستثمرين لإضافتهم للمشروع
        return view('dashboard.projects.create', compact('investors'));
    }

    /**
     * حفظ مشروع جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'estimated_end_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_cost_usd' => 'nullable|numeric|min:0',
            'attachments.*' => 'nullable|file|max:5000', // 5MB max per file

            // قواعد التحقق للوحدات
            'units' => 'nullable|array',
            'units.*.unit_number' => 'required_with:units|string|max:255',
            'units.*.unit_type' => ['required_with:units', Rule::in(['apartment', 'villa', 'office', 'land', 'commercial'])],
            'units.*.area_sqm' => 'required_with:units|numeric|min:1',
            'units.*.expected_price_usd' => 'required_with:units|numeric|min:0',

            // قواعد التحقق للمستثمرين
            'investors' => 'nullable|array',
            'investors.*.investor_id' => 'required_with:investors|exists:investors,id',
            'investors.*.investment_percentage' => 'required_with:investors|numeric|min:0.01|max:100',
        ]);

        // استخدام DB::transaction لضمان حفظ جميع البيانات أو عدم حفظ أي منها
        try {
            DB::beginTransaction();

            // 1. معالجة المرفقات
            $attachmentsPaths = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('projects/attachments', 'public');
                    $attachmentsPaths[] = $path;
                }
            }

            // 2. إنشاء المشروع الرئيسي
            $project = Project::create([
                'name' => $request->name,
                'location' => $request->location,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'estimated_end_date' => $request->estimated_end_date,
                'duration_months' => $request->duration_months,
                'main_contractor' => $request->main_contractor,
                'architect' => $request->architect,
                'estimated_cost_usd' => $request->estimated_cost_usd ?? 0,
                'notes' => $request->notes,
                'attachments' => $attachmentsPaths,
                'completion_percentage' => $request->completion_percentage ?? 0,
                'status' => $request->status ?? 'planning',
            ]);

            // 3. حفظ الوحدات
            if ($request->units) {
                $unitsData = collect($request->units)->map(function ($unit) {
                    return [
                        'unit_number' => $unit['unit_number'],
                        'unit_type' => $unit['unit_type'],
                        'floor_number' => $unit['floor_number'] ?? null,
                        'area_sqm' => $unit['area_sqm'],
                        'expected_price_usd' => $unit['expected_price_usd'],
                        'specifications' => $unit['specifications'] ?? null,
                        'status' => $unit['status'] ?? 'available',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();

                $project->units()->createMany($unitsData);
            }

            // 4. ربط المستثمرين
            if ($request->investors) {
                $investorsData = collect($request->investors)->mapWithKeys(function ($investor) {
                    return [
                        $investor['investor_id'] => [
                            'investment_percentage' => $investor['investment_percentage'],
                            'invested_amount' => $investor['invested_amount'] ?? 0,
                            'notes' => $investor['notes'] ?? null,
                        ]
                    ];
                })->toArray();

                $project->investors()->attach($investorsData);
            }

            DB::commit();

            return redirect()->route('dashboard.projects.show', $project->id)
                ->with('success', 'تم إنشاء المشروع بنجاح مع الوحدات والمستثمرين.');

        } catch (\Exception $e) {
            DB::rollBack();
            // يمكنك تسجيل الخطأ هنا: \Log::error($e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ أثناء حفظ المشروع: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل مشروع محدد.
     */
    public function show(Project $project)
    {
        // حساب الإحصائيات لعرضها في الواجهة
        $totalUnits = $project->units->count();
        $unitsSold = $project->units->where('status', 'sold')->count();
        $unitsAvailable = $totalUnits - $unitsSold;

        // حساب إجمالي القيمة المتوقعة للوحدات
        $totalExpectedValue = $project->units->sum('expected_price_usd');

        // إضافة حقل افتراضي لنسبة الإنجاز إذا لم يكن موجوداً في قاعدة البيانات
        if (!isset($project->completion_percentage)) {
            $project->completion_percentage = 65; // قيمة افتراضية للاختبار
        }

        return view('dashboard.projects.show', compact(
            'project',
            'totalUnits',
            'unitsSold',
            'unitsAvailable',
            'totalExpectedValue'
        ));
    }

}
