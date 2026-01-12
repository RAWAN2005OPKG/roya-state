<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Subcontractor;
use App\Models\SubcontractorContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SubcontractorController extends Controller
{
    /**
     * عرض قائمة جميع الموردين والمقاولين.
     */
    public function index(Request $request)
    {
        $query = Subcontractor::query();

        // تطبيق البحث إذا كان موجوداً
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('unique_id', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $subcontractors = $query->latest()->paginate(15);
        return view('dashboard.subcontractors.index', compact('subcontractors'));
    }

    /**
     * عرض نموذج إنشاء مورد جديد.
     */
    public function create()
    {
        // جلب قائمة المشاريع لإضافتها في العقود
        $projects = Project::select('id', 'name', 'location')->get();
        return view('dashboard.subcontractors.create', compact('projects'));
    }

    /**
     * تخزين مورد جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'id_number' => 'nullable|string|max:255|unique:subcontractors,id_number',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'contracts' => 'nullable|array',
            'contracts.*.project_id' => 'required|exists:projects,id',
            'contracts.*.contract_date' => 'required|date',
            'contracts.*.contract_value' => 'required|numeric|min:0',
            'contracts.*.currency' => 'required|in:USD,JOD,ILS',
            'contracts.*.exchange_rate' => 'required|numeric|min:0',
            'contracts.*.contract_details' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // إنشاء المورد
            $subcontractor = Subcontractor::create($validated);

            // إضافة العقود إذا كانت موجودة
            if (!empty($validated['contracts'])) {
                foreach ($validated['contracts'] as $contractData) {
                    $subcontractor->contracts()->create([
                        'project_id' => $contractData['project_id'],
                        'contract_date' => $contractData['contract_date'],
                        'contract_value' => $contractData['contract_value'],
                        'currency' => $contractData['currency'],
                        'exchange_rate' => $contractData['exchange_rate'],
                        'value_in_ils' => $contractData['contract_value'] * $contractData['exchange_rate'],
                        'contract_details' => $contractData['contract_details'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('dashboard.subcontractors.index')->with('success', 'تم إضافة المورد بنجاح.');

        } catch (Throwable $e) {
            DB::rollBack();
            // لعرض الخطأ بشكل واضح أثناء التطوير
            return back()->withInput()->with('error', 'حدث خطأ ما. ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة تفاصيل مورد معين.
     */
    public function show(Subcontractor $subcontractor)
    {
        // تحميل العلاقات لتجنب استعلامات N+1
        $subcontractor->load(['contracts.project', 'expenses']);
        return view('dashboard.subcontractors.show', compact('subcontractor'));
    }

    /**
     * عرض نموذج تعديل بيانات مورد.
     */
    public function edit(Subcontractor $subcontractor)
    {
        $projects = Project::select('id', 'name', 'location')->get();
        $subcontractor->load('contracts'); // تحميل العقود الحالية
        return view('dashboard.subcontractors.edit', compact('subcontractor', 'projects'));
    }

    /**
     * تحديث بيانات مورد في قاعدة البيانات.
     */
    public function update(Request $request, Subcontractor $subcontractor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'id_number' => 'nullable|string|max:255|unique:subcontractors,id_number,' . $subcontractor->id,
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            // ... يمكن إضافة قواعد التحقق للعقود هنا بنفس طريقة دالة store
        ]);

        $subcontractor->update($validated);
        // ... يمكن إضافة منطق تحديث العقود هنا

        return redirect()->route('dashboard.subcontractors.show', $subcontractor->id)->with('success', 'تم تحديث بيانات المورد بنجاح.');
    }

    /**
     * حذف مورد (نقل إلى سلة المحذوفات).
     */
    public function destroy(Subcontractor $subcontractor)
    {
        $subcontractor->delete();
        return redirect()->route('dashboard.subcontractors.index')->with('success', 'تم نقل المورد إلى سلة المحذوفات.');
    }

    /**
     * عرض سلة المحذوفات.
     */
    public function trash()
    {
        $trashedSubcontractors = Subcontractor::onlyTrashed()->latest()->paginate(10);
        return view('dashboard.subcontractors.trash', compact('trashedSubcontractors'));
    }

    /**
     * استعادة مورد من سلة المحذوفات.
     */
    public function restore($id)
    {
        Subcontractor::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('dashboard.subcontractors.trash')->with('success', 'تم استعادة المورد بنجاح.');
    }

    /**
     * حذف مورد بشكل نهائي.
     */
    public function forceDelete($id)
    {
        Subcontractor::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('dashboard.subcontractors.trash')->with('success', 'تم حذف المورد نهائياً.');
    }
}
