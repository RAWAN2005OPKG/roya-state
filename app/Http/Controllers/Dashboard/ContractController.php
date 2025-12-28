<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Investor;
use App\Models\Subcontractor;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ContractController extends Controller
{
    /**
     * عرض قائمة بالعقود.
     * (يتضمن البحث والتصفية والترقيم)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */

public function index(Request $request)
{
    $query = Contract::with(['contractable', 'project']);

    // منطق البحث
    $search = $request->input('search');
    if ($search) {
        $query->where('contract_id', 'LIKE', "%{$search}%")
              ->orWhereHas('project', function ($q) use ($search) {
                  $q->where('project_name', 'LIKE', "%{$search}%");
              })
              ->orWhereHasMorph('contractable', [Customer::class, Investor::class, Subcontractor::class], function ($q) use ($search) {
                  $q->where('name', 'LIKE', "%{$search}%");
              });
    }

    // جلب العقود مع تقسيم الصفحات
    $contracts = $query->latest()->paginate(15);

    // حساب الإحصائيات المطلوبة في الواجهة
    $totalContracts = Contract::count();
    $totalValue = Contract::sum('investment_amount');

    // إرسال كل المتغيرات إلى الواجهة
    return view('dashboard.contracts.index', compact(
        'contracts',
        'search',
        'totalContracts',
        'totalValue'
    ));
}

    /**
     * عرض نموذج إنشاء عقد جديد.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $projects = Project::all();
        $customers = Customer::all();
        $investors = Investor::all();
        $subcontractors = Subcontractor::all();

        return view('dashboard.contracts.create', compact('projects', 'customers', 'investors', 'subcontractors'));
    }

    /**
     * تخزين عقد جديد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
   // في ContractController.php

public function store(Request $request)
{
    // 1. التحقق من صحة البيانات الأساسية
    $validated = $request->validate([
        'contract_type' => 'required|in:customer,investor,subcontractor',
        'contractable_id' => 'required|integer', // التأكد من وجوده
        'contract_id' => 'required|string|max:255|unique:contracts,contract_id',
        'project_id' => 'nullable|exists:projects,id',
        'signing_date' => 'required|date',
        'investment_amount' => 'required|numeric|min:0',
        'currency' => 'required|string|max:10',
        'status' => 'required|string|in:active,draft,completed,cancelled',
        'terms' => 'nullable|string',
        'attachment' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
    ]);

    // 2. تحديد نوع الموديل والتأكد من أن الـ ID صحيح
    $contractable_type_map = [
        'customer' => \App\Models\Customer::class,
        'investor' => \App\Models\Investor::class,
        'subcontractor' => \App\Models\Subcontractor::class,
    ];
    $modelClass = $contractable_type_map[$request->contract_type];
$modelClass = $contractable_type_map[$request->contract_type];
    if (! $modelClass::where('id', $request->contractable_id)->exists()) {
        return back()->withInput()->withErrors(['contractable_id' => 'الكيان المختار غير صالح.']);
    }
    $validated['contractable_type'] = $modelClass;

    // 3. جمع البيانات الإضافية في حقل 'details'
    $details = [];
    if ($request->contract_type == 'customer') {
        $details['unit_number'] = $request->customer_unit_number;
        $details['delivery_date'] = $request->customer_delivery_date;
    } elseif ($request->contract_type == 'investor') {
        $details['profit_percentage'] = $request->investor_profit_percentage;
        $details['duration_months'] = $request->investor_duration;
    } elseif ($request->contract_type == 'subcontractor') {
        $details['scope_of_work'] = $request->subcontractor_scope;
    }

    // ====================================================================
    //  ===> هذا هو السطر الذي يحل المشكلة <===
    // ====================================================================
    // نقوم بتحويل مصفوفة 'details' إلى نص بصيغة JSON يدويًا
    $validated['details'] = json_encode($details);
    // ====================================================================

    // 4. التعامل مع رفع الملف
    if ($request->hasFile('attachment')) {
        $path = $request->file('attachment')->store('contract_attachments', 'public');
        $validated['attachment'] = $path;
    }

    // 5. إنشاء العقد
    Contract::create($validated);

    return redirect()->route('dashboard.contracts.index')->with('success', 'تم إنشاء العقد بنجاح.');
}


    /**
     * عرض تفاصيل عقد محدد.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\View\View
     */
    public function show(Contract $contract)
    {
        // يتم تحميل العلاقات الضرورية مسبقاً
        $contract->load(['contractable', 'project', 'payments.fund']);

        // حساب القيم المالية (يفترض وجود هذه الدوال في نموذج Contract أو كـ Accessors)
        // $contract->total_paid;
        // $contract->remaining_amount;

        return view('dashboard.contracts.show', compact('contract'));
    }

    /**
     * عرض نموذج تعديل عقد محدد.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\View\View
     */
    public function edit(Contract $contract)
    {
        $projects = Project::all();
        $customers = Customer::all();
        $investors = Investor::all();
        $subcontractors = Subcontractor::all();
        $details = $contract->details ?? []; // استخراج التفاصيل المحفوظة

        return view('dashboard.contracts.edit', compact('contract', 'projects', 'customers', 'investors', 'subcontractors', 'details'));
    }

    /**
     * تحديث عقد محدد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Contract $contract)
    {
        $validatedData = $request->validate([
            'contract_type' => 'required|in:customer,investor,subcontractor',
            'contractable_id' => 'required|integer',
            'contract_id' => 'required|string|max:255|unique:contracts,contract_id,' . $contract->id,
            'signing_date' => 'required|date',
            'investment_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|in:ILS,USD,JOD',
            'project_id' => 'nullable|exists:projects,id',
            'status' => 'required|in:active,draft,completed,cancelled',
            'terms' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            // حقول العميل
            'customer_unit_number' => 'nullable|string|max:255',
            'customer_delivery_date' => 'nullable|date',
            // حقول المستثمر
            'investor_profit_percentage' => 'nullable|numeric|min:0|max:100',
            'investor_duration' => 'nullable|integer|min:1',
            // حقول المقاول
            'subcontractor_scope' => 'nullable|string',
        ]);

        $contractableType = $this->getContractableType($validatedData['contract_type']);
        $details = $this->extractDetails($validatedData);

        try {
            DB::beginTransaction();

            $contract->fill($validatedData);
            $contract->contractable_type = $contractableType;
            $contract->contractable_id = $validatedData['contractable_id'];
            $contract->details = $details;

            if ($request->hasFile('attachment')) {
                // حذف الملف القديم إذا كان موجوداً
                if ($contract->attachment) {
                    Storage::disk('public')->delete($contract->attachment);
                }
                $contract->attachment = $request->file('attachment')->store('contracts', 'public');
            }

            $contract->save();

            DB::commit();
            return redirect()->route('dashboard.contracts.show', $contract->id)->with('success', 'تم تحديث العقد بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث العقد: ' . $e->getMessage());
        }
    }

    /**
     * حذف عقد محدد (Soft Delete).
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('dashboard.contracts.index')->with('success', 'تم نقل العقد إلى سلة المحذوفات بنجاح.');
    }

    /**
     * عرض سلة محذوفات العقود.
     *
     * @return \Illuminate\View\View
     */
    public function trash()
    {
        // عرض العقود المحذوفة فقط
        $contracts = Contract::onlyTrashed()->paginate(10);

        return view('dashboard.contracts.trash', compact('contracts'));
    }

    /**
     * استعادة عقد محذوف.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $contract = Contract::withTrashed()->findOrFail($id);
        $contract->restore();
        return redirect()->route('dashboard.contracts.trash')->with('success', 'تم استعادة العقد بنجاح.');
    }

    /**
     * حذف عقد نهائياً من قاعدة البيانات.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete($id)
    {
        $contract = Contract::withTrashed()->findOrFail($id);
        // حذف الملف المرفق من التخزين
        if ($contract->attachment) {
             Storage::disk('public')->delete($contract->attachment);
        }
        $contract->forceDelete();
        return redirect()->route('dashboard.contracts.trash')->with('success', 'تم حذف العقد نهائياً بنجاح.');
    }



    /**
     * تحديد اسم النموذج المرتبط بناءً على نوع العقد.
     *
     * @param string $type
     * @return string
     */
    protected function getContractableType(string $type): string
    {
        return match ($type) {
            'customer' => Customer::class,
            'investor' => Investor::class,
            'subcontractor' => Subcontractor::class,
            default => throw new \InvalidArgumentException("Invalid contract type: $type"),
        };
    }

    /**
     * استخراج التفاصيل الخاصة بالنوع لحفظها كـ JSON.
     *
     * @param array $data
     * @return array
     */
    protected function extractDetails(array $data): array
    {
        $details = [];
        $type = $data['contract_type'];

        if ($type === 'customer') {
            $details['customer_unit_number'] = $data['customer_unit_number'] ?? null;
            $details['customer_delivery_date'] = $data['customer_delivery_date'] ?? null;
        } elseif ($type === 'investor') {
            $details['investor_profit_percentage'] = $data['investor_profit_percentage'] ?? null;
            $details['investor_duration'] = $data['investor_duration'] ?? null;
        } elseif ($type === 'subcontractor') {
            $details['subcontractor_scope'] = $data['subcontractor_scope'] ?? null;
        }

        return $details;
    }
}
