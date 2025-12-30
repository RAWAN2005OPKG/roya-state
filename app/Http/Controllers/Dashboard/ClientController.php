<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ProjectUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('id', 'desc')->paginate(10);
        return view('dashboard.clients.index', compact('clients'));
    }

    public function create()
    {
        // جلب الوحدات المتاحة فقط للبيع
        $availableUnits = ProjectUnit::where('status', 'available')->get();
        return view('dashboard.clients.create', compact('availableUnits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|unique:clients,id_number',
            'address' => 'nullable|string|max:255',

            'units' => 'required|array',
            'units.*.unit_id' => 'required|exists:project_units,id',
            'units.*.sale_price' => 'required|numeric|min:0',
            'units.*.currency' => 'required|in:USD,SAR,EUR',
            'units.*.sale_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // 1. إنشاء العميل
            $client = Client::create($request->only(['name', 'phone', 'id_number', 'address', 'notes']));

            // 2. ربط الوحدات وتحديث حالتها
            $unitsData = [];
            $unitIdsToUpdate = [];

            foreach ($request->units as $unitSale) {
                $unitsData[$unitSale['unit_id']] = [
                    'sale_price' => $unitSale['sale_price'],
                    'currency' => $unitSale['currency'],
                    'sale_date' => $unitSale['sale_date'],
                    'contract_details' => $unitSale['contract_details'] ?? null,
                ];
                $unitIdsToUpdate[] = $unitSale['unit_id'];
            }

            $client->units()->attach($unitsData);

            // تحديث حالة الوحدات إلى 'sold'
            ProjectUnit::whereIn('id', $unitIdsToUpdate)->update(['status' => 'sold']);

            DB::commit();

            return redirect()->route('dashboard.clients.index')
                ->with('success', 'تم إضافة العميل وعمليات البيع بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء حفظ العميل: ' . $e->getMessage());
        }
    }
}
