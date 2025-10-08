<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
public function index(Request $request)
    {
        $query = Customer::query();
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('project', 'LIKE', "%{$search}%");
        }

        $customers = $query->orderBy($sortBy, $sortOrder)->paginate(15);

        $totalClients = Customer::count();
        $totalAgreements = Customer::sum('agreement_amount');

        return view('dashboard.customers.index', compact(
            'customers', 'totalClients', 'totalAgreements',
            'search', 'sortBy', 'sortOrder'
        ));
    }

    public function create()
    {
        return view('dashboard.customers.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'due_date' => ['nullable', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'project' => ['nullable', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'agreement_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'],
            'currency' => ['required', 'string', 'max:10'],
            'paid_to' => ['nullable', 'string', 'max:100'],
            'paid_to_other' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'other_bank_name' => ['nullable', 'string', 'max:100'],
            'other_bank_branch' => ['nullable', 'string', 'max:100'],
            'check_number' => ['nullable', 'string', 'max:100'],
            'check_bank' => ['nullable', 'string', 'max:100'],
            'check_due_date' => ['nullable', 'date'],
            'check_receipt_date' => ['nullable', 'date'],
            'contract_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);


        if ($request->hasFile('contract_file')) {
            $validated['contract_file'] = $request->file('contract_file')->store('customer_contracts', 'public');
        }

        Customer::create($validated);
        return redirect()->route('dashboard.customers.index')->with('success', 'تم إضافة العميل بنجاح.');
    }

    public function show(Customer $customer)
    {
        return view('dashboard.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('dashboard.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contract_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);

        if ($request->hasFile('contract_file')) {
            if ($customer->contract_file) {
                Storage::disk('public')->delete($customer->contract_file);
            }
            $validated['contract_file'] = $request->file('contract_file')->store('customer_contracts', 'public');
        }

        $customer->update($validated);
        return redirect()->route('dashboard.customers.index')->with('success', 'تم تحديث العميل بنجاح.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'تم نقل العميل إلى سلة المحذوفات.');
    }

    public function trash()
    {
        $trashedCustomers = Customer::onlyTrashed()->latest('deleted_at')->paginate(15);
        return view('dashboard.customers.trash', ['customers' => $trashedCustomers]);
    }

    public function restore($id)
    {
        Customer::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'تم استعادة العميل بنجاح.');
    }

    public function forceDelete($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);
        if ($customer->contract_file) {
            Storage::disk('public')->delete($customer->contract_file);
        }
        $customer->forceDelete();
        return back()->with('success', 'تم حذف العميل نهائياً.');
    }

    public function exportExcel()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }
}
