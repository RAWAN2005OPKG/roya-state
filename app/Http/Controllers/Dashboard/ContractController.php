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
        $search = $request->input('search');

        $contractsQuery = Contract::with(['contractable', 'project'])
            ->latest();

        if ($search) {
            $contractsQuery->where('contract_id', 'like', '%' . $search . '%')
                           ->orWhereHasMorph('contractable', [Customer::class, Investor::class, Subcontractor::class], function ($query, $type) use ($search) {
                               $query->where('name', 'like', '%' . $search . '%');
                           })
                           ->orWhereHas('project', function ($query) use ($search) {
                               $query->where('project_name', 'like', '%' . $search . '%');
                           });
        }

        $contracts = $contractsQuery->paginate(10);        $totalContracts = Contract::count();
        $totalValue = Contract::where('currency', 'ILS')->sum('investment_amount');

        return view('dashboard.contracts.index', compact('contracts', 'totalContracts', 'totalValue', 'search'));
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
     public function store(Request $request)
    {
        // 1. التحقق من صحة البيانات (Validation)
        $validatedData = $request->validate([
            'contract_type' => 'required|in:customer,investor,subcontractor',
            'contractable_id' => 'required|integer',
            'contract_id' => 'required|string|max:255|unique:contracts,contract_id',
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

        // 2. معالجة البيانات المساعدة
        $contractableType = $this->getContractableType($validatedData['contract_type']);
        $details = $this->extractDetails($validatedData);

        try {
            DB::beginTransaction();

            // 3. إنشاء وحفظ العقد
            $contract = new Contract($validatedData);
            $contract->contractable_type = $contractableType;
            $contract->contractable_id = $validatedData['contractable_id'];
            $contract->details = $details; 

            // 4. معالجة المرفقات
            if ($request->hasFile('attachment')) {
                $contract->attachment = $request->file('attachment')->store('contracts', 'public');
            }

            $contract->save();

            DB::commit();

            return redirect()->route('dashboard.contracts.index')->with('success', 'تم إنشاء العقد بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Contract Store Error: " . $e->getMessage(), [
                'request_data' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء حفظ العقد. يرجى مراجعة سجلات النظام.');
        }
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

        // ملاحظة: الـ client_name في View سلة المحذوفات غير موجود في نموذج Contract
        // يجب إضافة Accessor أو تعديل الاستعلام لجلب الاسم.
        // في هذا المثال، سنفترض أننا أضفنا Accessor مؤقتًا في نموذج Contract

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


    // =========================================================================
    // دوال مساعدة خاصة بالـ Polymorphic Relation والتفاصيل الإضافية
    // =========================================================================

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
