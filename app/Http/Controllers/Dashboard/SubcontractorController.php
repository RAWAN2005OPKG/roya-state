<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Subcontractor;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SubcontractorController extends Controller
{
    public function index(Request $request)
    {
        $query = Subcontractor::with(['projects', 'payments']);

        if ($request->filled('search_id')) {
            $query->where('unique_id', 'like', '%' . $request->search_id . '%');
        }
        if ($request->filled('search_id_number')) {
            $query->where('id_number', 'like', '%' . $request->search_id_number . '%');
        }

        $subcontractors = $query->orderBy('created_at', 'desc')->get();
        return view('dashboard.subcontractors.index', compact('subcontractors'));
    }

    public function create()
    {
        $projects = Project::select('id', 'name', 'location')->get();
        return view('dashboard.subcontractors.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'id_number' => 'nullable|string|max:50|unique:subcontractors,id_number',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'contracts' => 'nullable|array',
            'contracts.*.project_id' => 'required|exists:projects,id',
            'contracts.*.contract_value' => 'required|numeric|min:0',
            'contracts.*.currency' => 'required|string|max:10',
            'contracts.*.exchange_rate' => ['required_if:contracts.*.currency,USD', 'required_if:contracts.*.currency,JOD', 'nullable', 'numeric', 'min:0'],
            'contracts.*.contract_details' => 'nullable|string',
            'contracts.*.contract_date' => 'required|date',
        ]);

        try {
            $subcontractor = DB::transaction(function () use ($validatedData) {
                $subcontractor = Subcontractor::create($validatedData);

                if (isset($validatedData['contracts'])) {
                    $contractsToAttach = [];
                    foreach ($validatedData['contracts'] as $contract) {
                        $currency = $contract['currency'];
                        $exchangeRate = ($currency === 'ILS') ? 1 : ($contract['exchange_rate'] ?? 1);
                        $contractValue = $contract['contract_value'];

                        $contractsToAttach[$contract['project_id']] = [
                            'contract_value' => $contractValue,
                            'currency' => $currency,
                            'exchange_rate' => $exchangeRate,
                            'contract_value_ils' => $contractValue * $exchangeRate,
                            'contract_details' => $contract['contract_details'],
                            'contract_date' => $contract['contract_date'],
                        ];
                    }
                    $subcontractor->projects()->attach($contractsToAttach);
                }
                return $subcontractor;
            });

            return redirect()->route('dashboard.subcontractors.index')
                ->with('success', "تم حفظ المقاول '{$subcontractor->name}' بنجاح.");

        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'فشل الحفظ. خطأ تقني: ' . $e->getMessage());
        }
    }
}
