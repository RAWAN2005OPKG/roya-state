<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContractsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractController extends Controller
{

public function index(Request $request)
{
    $query = Contract::query();

    // 1. تعريف المتغيرات في البداية (هذا هو الحل)
    $search = $request->input('search');
    $sortBy = $request->input('sort_by', 'signing_date'); // قيمة افتراضية
    $sortOrder = $request->input('sort_order', 'desc');   // قيمة افتراضية

    // البحث
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('client_name', 'LIKE', "%{$search}%")
              ->orWhere('contract_id', 'LIKE', "%{$search}%")
              ->orWhere('client_phone', 'LIKE', "%{$search}%");
        });
    }

    $query->orderBy($sortBy, $sortOrder);

    $contracts = $query->paginate(15);

    $allContracts = Contract::all();
    $totalContracts = $allContracts->count();
    $totalValue = $allContracts->sum('investment_amount');
    $totalPaid = 0;
    $totalRemaining = $totalValue - $totalPaid;

    return view('dashboard.contracts.index', compact(
        'contracts', 'totalContracts', 'totalValue', 'totalPaid', 'totalRemaining',
        'search', 'sortBy', 'sortOrder' ,
    ));
}
    public function create()
    {
        return view('dashboard.contracts.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => ['required', 'string', 'max:255', 'unique:contracts,contract_id'],
            'signing_date' => ['required', 'date'],
            'status' => ['nullable', 'in:active,draft'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_phone' => ['required', 'string', 'max:50'],
            'client_alt_phone' => ['nullable', 'string', 'max:50'],
            'client_id_number' => ['required', 'string', 'max:100'],
            'property_type' => ['nullable', 'string', 'max:255'],
            'property_location' => ['nullable', 'string', 'max:255'],
            'investment_amount' => ['required', 'numeric', 'min:0'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,bank_transaction,check'],
            'apartment_price' => ['nullable', 'numeric', 'min:0'],
            'first_payment_date' => ['nullable', 'date'],
            'down_payment_initial' => ['nullable', 'numeric', 'min:0'],
            'down_payment_other' => ['nullable', 'numeric', 'min:0'],
            'profit_percentage' => ['nullable', 'numeric', 'min:0'],
            'remaining_amount' => ['nullable', 'numeric', 'min:0'],
            'cash_amount' => ['nullable', 'numeric', 'min:0', 'required_if:payment_method,cash'],
            'cash_receipt_number' => ['nullable', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255', 'required_if:payment_method,bank_transaction'],
            'account_number' => ['nullable', 'string', 'max:255', 'required_if:payment_method,bank_transaction'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'transfer_date' => ['nullable', 'date'],
            'check_number' => ['nullable', 'string', 'max:255', 'required_if:payment_method,check'],
            'check_amount' => ['nullable', 'numeric', 'min:0', 'required_if:payment_method,check'],
            'check_holder' => ['nullable', 'string', 'max:255'],
            'check_bank' => ['nullable', 'string', 'max:255'],
            'check_bank_branch' => ['nullable', 'string', 'max:255'],
            'check_due_date' => ['nullable', 'date'],
            'check_receipt_date' => ['nullable', 'date'],
        ]);
        if ($request->has('payment_methods')) {
            $validated['payment_method'] = implode(',', $request->payment_methods);
        }

        Contract::create($validated);
        return redirect()->route('dashboard.contracts.index')->with('success', 'تم إنشاء العقد بنجاح!');
    }
 public function show(Contract $contract)
    {
        return view('dashboard.contracts.show', compact('contract'));
    }
    public function edit(Contract $contract)
    {
        return view('dashboard.contracts.edit', compact('contract'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
             'contract_id' => ['required', 'string', 'max:255', 'unique:contracts,contract_id'],
            'signing_date' => ['required', 'date'],
            'status' => ['nullable', 'in:active,draft'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_phone' => ['required', 'string', 'max:50'],
            'client_alt_phone' => ['nullable', 'string', 'max:50'],
            'client_id_number' => ['required', 'string', 'max:100'],
            'property_type' => ['nullable', 'string', 'max:255'],
            'property_location' => ['nullable', 'string', 'max:255'],
            'investment_amount' => ['required', 'numeric', 'min:0'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,bank_transaction,check'],
            'apartment_price' => ['nullable', 'numeric', 'min:0'],
            'first_payment_date' => ['nullable', 'date'],
            'down_payment_initial' => ['nullable', 'numeric', 'min:0'],
            'down_payment_other' => ['nullable', 'numeric', 'min:0'],
            'profit_percentage' => ['nullable', 'numeric', 'min:0'],
            'remaining_amount' => ['nullable', 'numeric', 'min:0'],
            'cash_amount' => ['nullable', 'numeric', 'min:0', 'required_if:payment_method,cash'],
            'cash_receipt_number' => ['nullable', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255', 'required_if:payment_method,bank_transaction'],
            'account_number' => ['nullable', 'string', 'max:255', 'required_if:payment_method,bank_transaction'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'transfer_date' => ['nullable', 'date'],
            'check_number' => ['nullable', 'string', 'max:255', 'required_if:payment_method,check'],
            'check_amount' => ['nullable', 'numeric', 'min:0', 'required_if:payment_method,check'],
            'check_holder' => ['nullable', 'string', 'max:255'],
            'check_bank' => ['nullable', 'string', 'max:255'],
            'check_bank_branch' => ['nullable', 'string', 'max:255'],
            'check_due_date' => ['nullable', 'date'],
            'check_receipt_date' => ['nullable', 'date'],
        ]);

        if ($request->has('payment_methods')) {
            $validated['payment_method'] = implode(',', $request->payment_methods);
        } else {
            $validated['payment_method'] = null; // إذا لم يتم تحديد أي طريقة
        }

        $contract->update($validated);
        return redirect()->route('dashboard.contracts.index')->with('success', 'تم تحديث العقد بنجاح!');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return back()->with('success', 'تم نقل العقد إلى سلة المحذوفات.');
    }

    public function trash()
    {
        $trashedContracts = Contract::onlyTrashed()->latest('deleted_at')->paginate(15);
        return view('dashboard.contracts.trash', ['contracts' => $trashedContracts]);
    }

    public function restore($id)
    {
        Contract::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'تم استعادة العقد بنجاح.');
    }

    public function forceDelete($id)
    {
        Contract::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'تم حذف العقد نهائياً.');
    }

    public function exportExcel()
    {
        return Excel::download(new ContractsExport, 'contracts.xlsx');
    }

    public function exportPdf()
    {
        $contracts = Contract::all();
        $pdf = Pdf::loadView('dashboard.contracts.pdf', compact('contracts'));
        return $pdf->download('contracts-report.pdf');
    }
}
