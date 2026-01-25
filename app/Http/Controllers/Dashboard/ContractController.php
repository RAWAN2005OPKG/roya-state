<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Contract; // للعقود القديمة (العملاء)
use App\Models\GeneralContract; // للعقود الجديدة (مورد، مستثمر)
use App\Models\Project;
use App\Models\Client;
use App\Models\Investor;
use App\Models\Subcontractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ContractController extends Controller
{
    /**
     * عرض قائمة بكل العقود.
     */
    public function index(Request $request)
    {
        // ملاحظة: هذه الدالة ستعرض العقود الجديدة فقط حالياً للتبسيط
        // يمكن لاحقاً دمج عقود العملاء القديمة معها
        $contracts = GeneralContract::with(['contractable', 'project'])->latest()->paginate(15);

        return view('dashboard.contracts.index', compact('contracts'));
    }

    /**
     * عرض نموذج إنشاء عقد جديد.
     */
    public function create()
    {
        $projects = Project::select('id', 'name')->get();
        return view('dashboard.contracts.create', compact('projects'));
    }

    /**
     * تخزين العقد الجديد في قاعدة البيانات.
     * هذه هي الدالة التي كانت مفقودة.
     */
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
                $dataToSave['client_id'] = $validatedData['contractable_id'];
                $dataToSave['project_unit_id'] = $request->project_unit_id ?? 1; // قيمة مؤقتة
                Contract::create($dataToSave);
            } else {
                $dataToSave['contractable_type'] = $modelClass;
                $dataToSave['contractable_id'] = $validatedData['contractable_id'];
                GeneralContract::create($dataToSave);
            }

            return redirect()->route('dashboard.contracts.index')->with('success', 'تم حفظ العقد بنجاح.');

        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'فشل حفظ العقد: ' . $e->getMessage());
        }
    }

    /**
     * دالة AJAX لجلب الكيانات للبحث.
     */
    public function getContractables(Request $request)
    {
        $request->validate(['type' => 'required|string']);
        $modelName = 'App\\Models\\' . $request->type;
        if (!class_exists($modelName)) { return response()->json(['items' => []]); }
        $query = $modelName::query();
        if ($search = $request->query('q')) { $query->where('name', 'LIKE', "%{$search}%")->orWhere('unique_id', 'LIKE', "%{$search}%"); }
        if ($id = $request->query('id')) { $query->where('id', $id); }
        $items = $query->select('id', 'name', 'unique_id')->take(10)->get()->map(fn($item) => ['id' => $item->id, 'text' => "{$item->name} ({$item->unique_id})"]);
        return response()->json(['items' => $items]);
    }
}
