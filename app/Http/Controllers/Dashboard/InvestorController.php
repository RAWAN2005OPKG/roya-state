<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class InvestorController extends Controller
{
    public function index(Request $request)
    {
        $query = Investor::with('projects');

        if ($request->filled('search_id')) {
            $query->where('unique_id', 'like', '%' . $request->search_id . '%');
        }
        if ($request->filled('search_id_number')) {
            $query->where('id_number', 'like', '%' . $request->search_id_number . '%');
        }

        $investors = $query->orderBy('created_at', 'desc')->get();

        return view('dashboard.investors.index', compact('investors'));
    }

    public function create()
    {
        $projects = Project::select('id', 'name', 'location')->get();
        return view('dashboard.investors.create', compact('projects'));
    }

   // ... داخل InvestorController.php

public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'id_number' => 'nullable|string|max:50|unique:investors,id_number',
        'phone' => 'nullable|string|max:20',
        'company' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'projects' => 'nullable|array',
        'projects.*.project_id' => 'required|exists:projects,id',
        'projects.*.investment_percentage' => 'nullable|numeric|min:0|max:100',
        'projects.*.invested_amount' => 'required|numeric|min:0',
        'projects.*.currency' => 'required|string|max:10',
        'projects.*.notes' => 'nullable|string',
        // إضافة التحقق لسعر الصرف
        'projects.*.exchange_rate' => ['required_if:projects.*.currency,USD', 'required_if:projects.*.currency,JOD', 'nullable', 'numeric', 'min:0'],
    ]);

    try {
        $investor = DB::transaction(function () use ($validatedData) {
            $investor = Investor::create($validatedData);

            if (isset($validatedData['projects'])) {
                $projectsToAttach = [];
                foreach ($validatedData['projects'] as $investment) {
                    $currency = $investment['currency'];
                    // إذا كانت العملة شيكل، سعر الصرف دائماً 1
                    $exchangeRate = ($currency === 'ILS') ? 1 : ($investment['exchange_rate'] ?? 1);
                    $investedAmount = $investment['invested_amount'];

                    $projectsToAttach[$investment['project_id']] = [
                        'investment_percentage' => $investment['investment_percentage'],
                        'invested_amount' => $investedAmount,
                        'currency' => $currency,
                        'notes' => $investment['notes'],
                        // --- حفظ البيانات الجديدة ---
                        'exchange_rate' => $exchangeRate,
                        'invested_amount_ils' => $investedAmount * $exchangeRate,
                    ];
                }
                $investor->projects()->attach($projectsToAttach);
            }
            return $investor;
        });

        return redirect()->route('dashboard.investors.index')
            ->with('success', "تم حفظ المستثمر '{$investor->name}' بنجاح.");

    } catch (Throwable $e) {
        return back()->withInput()->with('error', 'فشل الحفظ. خطأ تقني: ' . $e->getMessage());
    }
}

}
