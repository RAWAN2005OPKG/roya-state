<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Investor;
use App\Models\Subcontractor;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    /**
     * عرض قائمة بكل العقود.
     */
    public function index(Request $request)
    {
        $query = Contract::with(['contractable', 'project']);
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'signing_date');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('contract_id', 'LIKE', "%{$search}%")
                  ->orWhereHasMorph('contractable', [Customer::class, Investor::class, Subcontractor::class], function ($query) use ($search) {
                      $query->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('project', function ($query) use ($search) {
                      $query->where('project_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $contracts = $query->orderBy($sortBy, $sortOrder)->paginate(15);
        $totalContracts = Contract::count();
        $totalValue = Contract::sum('investment_amount');

        return view('dashboard.contracts.index', compact('contracts', 'totalContracts', 'totalValue', 'search', 'sortBy', 'sortOrder'));
    }

    /**
     * عرض صفحة إضافة عقد جديد.
     */
    public function create(Request $request)
    {
        $customers = Customer::orderBy('name')->get();
        $investors = Investor::orderBy('name')->get();
        $subcontractors = Subcontractor::orderBy('name')->get();
        $projects = Project::orderBy('project_name')->get();

        // لجعل النموذج يختار النوع وصاحب العقد تلقائيًا إذا جئنا من صفحة أخرى
        $prefilledType = $request->query('type');
        $prefilledId = $request->query('id');

        return view('dashboard.contracts.create', compact('customers', 'investors', 'subcontractors', 'projects', 'prefilledType', 'prefilledId'));
    }

    /**
     * تخزين العقد الجديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        return $this->handleContract($request);
    }

    /**
     * عرض صفحة تفاصيل العقد.
     */
    public function show(Contract $contract)
    {
        $contract->load(['contractable', 'project']);
        $details = json_decode($contract->details, true); // تحويل JSON إلى مصفوفة
        return view('dashboard.contracts.show', compact('contract', 'details'));
    }

    /**
     * عرض صفحة تعديل العقد.
     */
    public function edit(Contract $contract)
    {
        $customers = Customer::orderBy('name')->get();
        $investors = Investor::orderBy('name')->get();
        $subcontractors = Subcontractor::orderBy('name')->get();
        $projects = Project::orderBy('project_name')->get();

        $details = json_decode($contract->details, true);

        return view('dashboard.contracts.edit', compact('contract', 'customers', 'investors', 'subcontractors', 'projects', 'details'));
    }

    /**
     * تحديث العقد في قاعدة البيانات.
     */
    public function update(Request $request, Contract $contract)
    {
        return $this->handleContract($request, $contract);
    }

    /**
     * نقل العقد إلى سلة المحذوفات.
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('dashboard.contracts.index')->with('success', 'تم نقل العقد إلى سلة المحذوفات.');
    }

    /**
     * دالة مركزية لمعالجة إنشاء وتحديث العقود.
     */
    private function handleContract(Request $request, Contract $contract = null)
    {
        $isUpdate = $contract !== null;

        // --- 1. التحقق من الحقول المشتركة ---
        $validated = $request->validate([
            'contract_type' => ['required', 'in:customer,investor,subcontractor'],
            'contractable_id' => ['required', 'integer'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'contract_id' => ['required', 'string', 'max:255', $isUpdate ? Rule::unique('contracts')->ignore($contract->id) : 'unique:contracts,contract_id'],
            'signing_date' => ['required', 'date'],
            'status' => ['required', 'string'],
            'investment_amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string'],
            'terms' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:10240'],
        ]);

        // --- 2. التحقق من الحقول الديناميكية ---
        $details = [];
        if ($validated['contract_type'] === 'customer') {
            $details = $request->validate(['customer_unit_number' => ['nullable', 'string'], 'customer_delivery_date' => ['nullable', 'date']]);
        } elseif ($validated['contract_type'] === 'investor') {
            $details = $request->validate(['investor_profit_percentage' => ['nullable', 'numeric'], 'investor_duration' => ['nullable', 'integer']]);
        } elseif ($validated['contract_type'] === 'subcontractor') {
            $details = $request->validate(['subcontractor_scope' => ['nullable', 'string']]);
        }

        // --- 3. تحديد نوع المودل ---
        $contractable_type = match ($validated['contract_type']) {
            'customer' => Customer::class,
            'investor' => Investor::class,
            'subcontractor' => Subcontractor::class,
        };

        DB::beginTransaction();
        try {
            $contractData = $validated;
            $contractData['contractable_type'] = $contractable_type;
            $contractData['details'] = json_encode($details);

            if ($request->hasFile('attachment')) {
                if ($isUpdate && $contract->attachment) {
                    Storage::disk('public')->delete($contract->attachment);
                }
                $contractData['attachment'] = $request->file('attachment')->store('contracts', 'public');
            }

            if ($isUpdate) {
                $contract->update($contractData);
            } else {
                $contract = Contract::create($contractData);
            }

            DB::commit();
            $message = $isUpdate ? 'تم تحديث العقد بنجاح.' : 'تم إنشاء العقد بنجاح.';
            return redirect()->route('dashboard.contracts.index')->with('success', $message);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage())->withInput();
        }
    }
}
