<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ProjectUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount('contracts')->latest()->paginate(15);
        return view('dashboard.clients.index', compact('clients'));
    }

    public function create()
    {
        // جلب الوحدات المتاحة مع تفاصيل المشروع المرتبط بها
        $availableUnits = ProjectUnit::where('status', 'available')
                                     ->with('project:id,name')
                                     ->select('id', 'project_id', 'unit_number', 'floor', 'has_parking', 'finish_type')
                                     ->get();
        return view('dashboard.clients.create', compact('availableUnits'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'id_number' => 'nullable|string|unique:clients,id_number',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'units' => 'required|array|min:1',
            'units.*.unit_id' => 'required|exists:project_units,id',
            'units.*.sale_date' => 'required|date',
            'units.*.sale_price' => 'required|numeric|min:0',
            'units.*.currency' => 'required|string|in:ILS,USD,JOD',
            'units.*.exchange_rate' => 'required|numeric|min:0',
            'units.*.contract_details' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                $client = Client::create(collect($validatedData)->only(['name', 'phone', 'id_number', 'address', 'notes'])->toArray());

                foreach ($validatedData['units'] as $unitSale) {
                    $unit = ProjectUnit::findOrFail($unitSale['unit_id']);
                    if ($unit->status !== 'available') {
                        throw new \Exception("الوحدة رقم '{$unit->unit_number}' لم تعد متاحة.");
                    }

                    $amountILS = $unitSale['sale_price'] * $unitSale['exchange_rate'];

                    // إنشاء العقد وربطه بالعميل والوحدة
                    $client->contracts()->create([
                        'project_id' => $unit->project_id,
                        'project_unit_id' => $unit->id, // <-- ربط العقد بالوحدة
                        'contract_date' => $unitSale['sale_date'],
                        'contract_details' => $unitSale['contract_details'],
                        'investment_amount' => $unitSale['sale_price'],
                        'currency' => $unitSale['currency'],
                        'exchange_rate' => $unitSale['exchange_rate'],
                        'investment_amount_ils' => $amountILS,
                    ]);

                    $unit->update(['status' => 'sold']);
                }
            });
            return redirect()->route('dashboard.clients.index')->with('success', 'تم حفظ العميل والعقود بنجاح.');
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'فشل الحفظ: ' . $e->getMessage());
        }
    }
}
