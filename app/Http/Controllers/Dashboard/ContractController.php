<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Contract; 
use App\Models\SubcontractorContract; 
use App\Models\Project;
use App\Models\Client;
use App\Models\Investor;
use App\Models\Subcontractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with(['contractable', 'project']);
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', $searchTerm)
                  ->orWhereHas('project', fn($pq) => $pq->where('name', 'like', "%{$searchTerm}%"))
                  ->orWhereHasMorph('contractable', [Client::class, Investor::class, Subcontractor::class], fn($cq) => $cq->where('name', 'like', "%{$searchTerm}%")->orWhere('unique_id', 'like', "%{$searchTerm}%"));
            });
        }
        $totalContracts = (clone $query)->count();
$totalValueILS = (clone $query)->sum('contract_value');
        $perPage = $request->query('per_page', 10);
        $contracts = $query->latest()->paginate($perPage);
        return view('dashboard.contracts.index', compact('contracts', 'totalContracts', 'totalValueILS', 'request'));
    }

    public function create()
    {
        $projects = Project::select('id', 'name')->get();
        return view('dashboard.contracts.create', compact('projects'));
    }


public function store(Request $request)
{
    $validatedData = $request->validate([
        'contractable_type' => 'required|string|in:Client,Investor,Subcontractor',
        'contractable_id' => 'required|integer|min:1',
        'project_id' => 'nullable|exists:projects,id',
        'contract_date' => 'required|date',
        'contract_value' => 'required|numeric|min:0',
        'currency' => 'required|string|in:ILS,USD,JOD',
        'exchange_rate' => 'required|numeric|min:0',
        'contract_details' => 'nullable|string',
        'attachment' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
    ]);

    try {
        $modelClass = "App\\Models\\" . $validatedData['contractable_type'];
        if (!class_exists($modelClass) || !$modelClass::find($validatedData['contractable_id'])) {
            throw new \Exception("الكيان المختار غير موجود أو النوع غير صالح.");
        }
        
        $attachmentPath = $request->hasFile('attachment') ? $request->file('attachment')->store('contracts_attachments', 'public') : null;

        $dataToSave = [
            'project_id' => $validatedData['project_id'],
            'contract_date' => $validatedData['contract_date'],
            'contract_value' => $validatedData['contract_value'],
            'currency' => $validatedData['currency'],
            'exchange_rate' => $validatedData['exchange_rate'],
            'contract_details' => $validatedData['contract_details'],
            'attachment' => $attachmentPath,
        ];

    
        if ($validatedData['contractable_type'] === 'Client') {
            // إذا كان عميل، احفظ في الجدول القديم (مع إضافة الحقول الإجبارية)
            $dataToSave['client_id'] = $validatedData['contractable_id'];
            // يجب إضافة project_unit_id هنا، سنفترض أنه 1 مؤقتاً
            $dataToSave['project_unit_id'] = $request->project_unit_id ?? 1; // يجب تعديل الواجهة لتشمل هذا
            Contract::create($dataToSave);
        } else {
            // إذا كان مستثمر أو مورد، احفظ في الجدول الجديد
            $dataToSave['contractable_type'] = $modelClass;
            $dataToSave['contractable_id'] = $validatedData['contractable_id'];
            GeneralContract::create($dataToSave);
        }

        return redirect()->route('dashboard.contracts.index')->with('success', 'تم حفظ العقد بنجاح.');

    } catch (Throwable $e) {
        return back()->withInput()->with('error', 'فشل حفظ العقد: ' . $e->getMessage());
    }
}




  public function show(Contract $contract)
    {
        // استخدام Eager Loading لتحميل كل العلاقات المطلوبة بكفاءة
        $contract->load([
            'contractable', // صاحب العقد
            'project',      // المشروع
            'projectUnit',  // الوحدة السكنية
            'payments' => function ($query) {
                $query->latest('payment_date'); // الدفعات مرتبة من الأحدث
            }
        ]);

        return view('dashboard.contracts.show', compact('contract'));
    }

    /**
     * دالة AJAX لجلب الكيانات (عملاء، مستثمرين، مقاولين) للبحث.
     */
    public function getContractables(Request $request)
    {
        $request->validate(['type' => 'required|string']);

        $modelName = 'App\\Models\\' . $request->type;
        if (!class_exists($modelName)) {
            return response()->json(['items' => []]);
        }

        $query = $modelName::query();

        if ($search = $request->query('q')) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('unique_id', 'LIKE', "%{$search}%");
        }

        $items = $query->select('id', 'name', 'unique_id')->take(10)->get()->map(function ($item) {
            return ['id' => $item->id, 'text' => "{$item->name} ({$item->unique_id})"];
        });

        return response()->json(['items' => $items]);
    }

    public function edit(Contract $contract)
    {
        $contract->load('contractable', 'project');
        $projects = Project::select('id', 'name')->get();
        $selectedContractable = null;
        if ($contract->contractable) {
            $selectedContractable = [
                'id' => $contract->contractable->id,
                'text' => $contract->contractable->name . ' (ID: ' . $contract->contractable->unique_id . ')',
            ];
        }
        return view('dashboard.contracts.edit', compact('contract', 'projects', 'selectedContractable'));
    }

    public function update(Request $request, Contract $contract)
    {
        // يمكنك إضافة منطق التحديث هنا بنفس طريقة دالة store
        return redirect()->route('dashboard.contracts.index')->with('success', 'تم تحديث العقد بنجاح.');
    }

}
