<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ProjectUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query()->with(['contracts.projectUnit']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('unique_id', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(10);
        return view('dashboard.clients.index', compact('clients'));
    }

    public function create()
    {
        $availableUnits = ProjectUnit::where('status', 'available')->with('project:id,name')->get(['id', 'project_id', 'unit_number', 'floor', 'has_parking', 'finish_type']);
        return view('dashboard.clients.create', compact('availableUnits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_number' => 'nullable|string|max:20|unique:clients,id_number',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'contracts' => 'required|array|min:1',
            'contracts.*.unit_id' => 'required|exists:project_units,id',
            'contracts.*.sale_date' => 'required|date',
            'contracts.*.total_amount' => 'required|numeric|min:0',
            'contracts.*.currency' => 'required|in:USD,JOD,ILS',
            'contracts.*.exchange_rate' => 'required|numeric|min:0',
            'contracts.*.total_amount_ils' => 'required|numeric|min:0',
            'contracts.*.down_payment' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $client = Client::create($validated);

            foreach ($validated['contracts'] as $contractData) {
                $contract = $client->contracts()->create([
                    'project_unit_id' => $contractData['unit_id'],
                    'contract_date' => $contractData['sale_date'],
                    'total_amount' => $contractData['total_amount'],
                    'currency' => $contractData['currency'],
                    'exchange_rate' => $contractData['exchange_rate'],
                    'total_amount_ils' => $contractData['total_amount_ils'],
                    'status' => 'active',
                ]);

                ProjectUnit::where('id', $contractData['unit_id'])->update(['status' => 'sold']);

                if (isset($contractData['down_payment']) && $contractData['down_payment'] > 0) {
                    $client->payments()->create([
                        'contract_id' => $contract->id,
                        'type' => 'in',
                        'method' => 'cash',
                        'payment_date' => $contractData['sale_date'],
                        'amount' => $contractData['down_payment'],
                        'currency' => $contractData['currency'],
                        'exchange_rate' => $contractData['exchange_rate'],
                        'amount_ils' => $contractData['down_payment'] * $contractData['exchange_rate'],
                        'notes' => 'دفعة مقدمة عند توقيع العقد',
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('dashboard.clients.show', $client->id)->with('success', 'تم إنشاء العميل وعقوده بنجاح.');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'فشل حفظ البيانات. خطأ تقني: ' . $e->getMessage());
        }
    }

    public function show(Client $client)
    {
        $client->load(['contracts.projectUnit.project', 'payments' => function ($query) {
            $query->latest('payment_date');
        }]);
        return view('dashboard.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $client->load('contracts');
        $availableUnits = ProjectUnit::where('status', 'available')->orWhereIn('id', $client->contracts->pluck('project_unit_id'))->with('project:id,name')->get(['id', 'project_id', 'unit_number', 'floor', 'has_parking', 'finish_type']);
        return view('dashboard.clients.edit', compact('client', 'availableUnits'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_number' => 'nullable|string|max:20|unique:clients,id_number,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'contracts' => 'required|array|min:1',
            'contracts.*.id' => 'nullable|exists:contracts,id',
            'contracts.*.unit_id' => 'required|exists:project_units,id',
            'contracts.*.sale_date' => 'required|date',
            'contracts.*.total_amount' => 'required|numeric|min:0',
            'contracts.*.currency' => 'required|in:USD,JOD,ILS',
            'contracts.*.exchange_rate' => 'required|numeric|min:0',
            'contracts.*.total_amount_ils' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            $client->update($validated);
            $existingContractIds = [];

            foreach ($validated['contracts'] as $contractData) {
                $contract = $client->contracts()->updateOrCreate(
                    ['id' => $contractData['id'] ?? null],
                    [
                        'project_unit_id' => $contractData['unit_id'],
                        'contract_date' => $contractData['sale_date'],
                        'total_amount' => $contractData['total_amount'],
                        'currency' => $contractData['currency'],
                        'exchange_rate' => $contractData['exchange_rate'],
                        'total_amount_ils' => $contractData['total_amount_ils'],
                        'status' => 'active',
                    ]
                );
                $existingContractIds[] = $contract->id;
                ProjectUnit::where('id', $contractData['unit_id'])->update(['status' => 'sold']);
            }

            $contractsToDelete = $client->contracts()->whereNotIn('id', $existingContractIds)->get();
            foreach ($contractsToDelete as $contract) {
                ProjectUnit::where('id', $contract->project_unit_id)->update(['status' => 'available']);
                $contract->delete();
            }

            DB::commit();
            return redirect()->route('dashboard.clients.show', $client->id)->with('success', 'تم تحديث بيانات العميل بنجاح.');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'فشل تحديث البيانات. خطأ تقني: ' . $e->getMessage());
        }
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('dashboard.clients.index')->with('success', 'تم نقل العميل إلى سلة المحذوفات.');
    }

    public function trash()
    {
        $trashedClients = Client::onlyTrashed()->latest()->paginate(10);
        return view('dashboard.clients.trash', compact('trashedClients'));
    }

    public function restore($id)
    {
        Client::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('dashboard.clients.trash')->with('success', 'تم استعادة العميل بنجاح.');
    }

    public function forceDelete($id)
    {
        $client = Client::onlyTrashed()->findOrFail($id);
        // هنا يجب حذف العلاقات يدوياً إذا لم يتم ضبطها في قاعدة البيانات
        $client->contracts()->delete();
        $client->payments()->delete();
        $client->forceDelete();
        return redirect()->route('dashboard.clients.trash')->with('success', 'تم حذف العميل نهائياً.');
    }

    public function exportExcel()
    {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }

    public function exportWord(Client $client)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addTitle('ملف العميل: ' . $client->name, 1);
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $fileName = 'client_' . $client->id . '.docx';
        $objWriter->save($fileName);
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
