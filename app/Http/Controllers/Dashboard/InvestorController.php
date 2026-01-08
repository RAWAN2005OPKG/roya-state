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
        $query = Investor::query();
        if ($search = $request->query('search')) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('unique_id', 'LIKE', "%{$search}%")
                  ->orWhere('id_number', 'LIKE', "%{$search}%");
        }
        $investors = $query->withCount('projects')->latest()->paginate(15);
        return view('dashboard.investors.index', compact('investors'));
    }

    public function create()
    {
        $projects = Project::select('id', 'name')->get();
        return view('dashboard.investors.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string',
            'id_number' => 'nullable|string|unique:investors,id_number',
            'phone' => 'nullable|string',
            'notes' => 'nullable|string',
            'projects' => 'nullable|array',
            'projects.*.project_id' => 'required|exists:projects,id',
            'projects.*.invested_amount' => 'required|numeric|min:0',
            'projects.*.currency' => 'required|in:ILS,USD,JOD',
            'projects.*.exchange_rate' => 'required|numeric|min:0',
            'projects.*.investment_percentage' => 'nullable|numeric|min:0|max:100',
            'projects.*.notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                $investor = Investor::create(collect($validatedData)->except('projects')->toArray());

                if (!empty($validatedData['projects'])) {
                    $syncData = [];
                    foreach ($validatedData['projects'] as $proj) {
                        $amountILS = $proj['invested_amount'] * $proj['exchange_rate'];
                        $syncData[$proj['project_id']] = [
                            'invested_amount' => $proj['invested_amount'],
                            'currency' => $proj['currency'],
                            'exchange_rate' => $proj['exchange_rate'],
                            'invested_amount_ils' => $amountILS,
                            'investment_percentage' => $proj['investment_percentage'],
                            'notes' => $proj['notes'],
                        ];
                    }
                    $investor->projects()->sync($syncData);
                }
            });
            return redirect()->route('dashboard.investors.index')->with('success', 'تم حفظ المستثمر بنجاح.');
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'فشل حفظ المستثمر: ' . $e->getMessage());
        }
    }

    public function show(Investor $investor)
    {
        $investor->load(['projects', 'payments' => fn($q) => $q->latest('payment_date')]);
        return view('dashboard.investors.show', compact('investor'));
    }

    public function edit(Investor $investor)
    {
        $projects = Project::select('id', 'name')->get();
        $investor->load('projects');
        return view('dashboard.investors.edit', compact('investor', 'projects'));
    }

    public function update(Request $request, Investor $investor)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string',
            'id_number' => 'nullable|string|unique:investors,id_number,' . $investor->id,
            'phone' => 'nullable|string',
            'notes' => 'nullable|string',
            'projects' => 'nullable|array',
            'projects.*.project_id' => 'required|exists:projects,id',
            'projects.*.invested_amount' => 'required|numeric|min:0',
            'projects.*.currency' => 'required|in:ILS,USD,JOD',
            'projects.*.exchange_rate' => 'required|numeric|min:0',
            'projects.*.investment_percentage' => 'nullable|numeric|min:0|max:100',
            'projects.*.notes' => 'nullable|string',        ]);

        try {
            DB::transaction(function () use ($validatedData, $investor) {
                $investor->update(collect($validatedData)->except('projects')->toArray());

                $syncData = [];
                if (!empty($validatedData['projects'])) {
                    foreach ($validatedData['projects'] as $proj) {
                        $amountILS = $proj['invested_amount'] * $proj['exchange_rate'];
                        $syncData[$proj['project_id']] = [
                            'invested_amount' => $proj['invested_amount'],
                            'currency' => $proj['currency'],
                            'exchange_rate' => $proj['exchange_rate'],
                            'invested_amount_ils' => $amountILS,
                            'investment_percentage' => $proj['investment_percentage'],
                            'notes' => $proj['notes'],
                        ];
                    }
                }
                $investor->projects()->sync($syncData);
            });
            return redirect()->route('dashboard.investors.show', $investor->id)->with('success', 'تم تحديث بيانات المستثمر بنجاح.');
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'فشل تحديث المستثمر: ' . $e->getMessage());
        }
    }

    public function destroy(Investor $investor)
    {
        // يمكنك إضافة منطق للتحقق قبل الحذف
        $investor->delete();
        return redirect()->route('dashboard.investors.index')->with('success', 'تم حذف المستثمر بنجاح.');
    }
}
