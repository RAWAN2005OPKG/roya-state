<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ProjectUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable; //  لاستقبال كل أنواع الأخطاء

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with(['units.project'])->orderBy('created_at', 'desc')->get();
        return view('dashboard.clients.index', compact('clients'));
       if ($request->filled('search_id')) {
        $query->where('unique_id', 'like', '%' . $request->search_id . '%');
    }
    if ($request->filled('search_id_number')) {
        $query->where('id_number', 'like', '%' . $request->search_id_number . '%');
    }
    // --- نهاية منطق البحث ---

    // نفذ الاستعلام بعد إضافة شروط البحث
    $clients = $query->orderBy('created_at', 'desc')->get();

    return view('dashboard.clients.index', compact('clients'));
}


    public function create()
    {
        $availableUnits = ProjectUnit::where('status', 'available')->with('project')->get();
        return view('dashboard.clients.create', compact('availableUnits'));
    }

    public function store(Request $request)
    {
        // التحقق من أن الوحدات المرسلة متاحة بالفعل (حماية إضافية)
        $unitIds = collect($request->input('units', []))->pluck('unit_id')->filter();
        if ($unitIds->isNotEmpty()) {
            $unavailableUnits = ProjectUnit::whereIn('id', $unitIds)->where('status', '!=', 'available')->pluck('unit_number')->implode(', ');
            if ($unavailableUnits) {
                return back()->withInput()->with('error', "خطأ: الوحدات التالية لم تعد متاحة: {$unavailableUnits}");
            }
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'id_number' => ['nullable', 'string', 'max:50', Rule::unique('clients', 'id_number')],
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',

            'units' => 'required|array|min:1',
            'units.*.unit_id' => 'required|distinct|exists:project_units,id',
            'units.*.sale_price' => 'required|numeric|min:0',
            'units.*.currency' => 'required|in:ILS,USD,JOD',
            'units.*.sale_date' => 'required|date',
            'units.*.contract_details' => 'nullable|string',
            'units.*.exchange_rate' => ['required_if:units.*.currency,USD', 'required_if:units.*.currency,JOD', 'nullable', 'numeric', 'min:0'],
        ]);

        try {
            // استخدام Transaction لضمان تنفيذ كل العمليات معاً أو لا شيء
            $client = DB::transaction(function () use ($validatedData) {
                // 1. إنشاء العميل
                $client = Client::create([
                    'name' => $validatedData['name'],
                    'phone' => $validatedData['phone'],
                    'id_number' => $validatedData['id_number'],
                    'address' => $validatedData['address'],
                    'notes' => $validatedData['notes'],
                    'unique_id' => 'CL-' . time()
                  ]);

                if (!$client) {
                    throw new \Exception("فشل إنشاء العميل.");
                }

                // 2. تجهيز بيانات الوحدات وربطها
                $unitsToAttach = [];
                foreach ($validatedData['units'] as $unitSale) {
                    $currency = $unitSale['currency'];
                    $exchangeRate = ($currency === 'ILS') ? 1 : ($unitSale['exchange_rate'] ?? 1);
                    $salePrice = $unitSale['sale_price'];

                    $unitsToAttach[$unitSale['unit_id']] = [
                        'sale_price' => $salePrice,
                        'currency' => $currency,
                        'exchange_rate' => $exchangeRate,
                        'sale_price_ils' => $salePrice * $exchangeRate,
                        'sale_date' => $unitSale['sale_date'],
                        'contract_details' => $unitSale['contract_details'] ?? null,
                    ];
                }

                $client->units()->attach($unitsToAttach);

                // 3. تحديث حالة الوحدات المباعة
                $unitIdsToUpdate = array_keys($unitsToAttach);
                ProjectUnit::whereIn('id', $unitIdsToUpdate)->update(['status' => 'sold']);

                return $client;
            });

            return redirect()->route('dashboard.clients.index')
                ->with('success', "تم حفظ العميل '{$client->name}' بنجاح.");

        } catch (Throwable $e) {
            // في حال حدوث أي خطأ، سيتم التراجع عن كل شيء وعرض رسالة خطأ مفصلة
            return back()->withInput()->with('error', 'فشل الحفظ. خطأ تقني: ' . $e->getMessage());
        }
    }
}
