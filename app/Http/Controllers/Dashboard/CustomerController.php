<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Validation\Rule;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Notifications\NewCustomerNotification;
use Illuminate\Support\Facades\Notification;
class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('contracts');
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
        }

        $customers = $query->orderBy($sortBy, $sortOrder)->paginate(15);
        $totalClients = Customer::count();
        $totalAgreements = Customer::sum('agreement_amount');

        return view('dashboard.customers.index', compact(
            'customers',
            'totalClients',
            'totalAgreements',
            'search',
            'sortBy',
            'sortOrder'
        ));
    }

    public function create()
    {
        return view('dashboard.customers.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255', 'unique:customers,email'],
            'address' => ['nullable', 'string', 'max:255'],
            'agreement_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
        ]);

        Customer::create($validated);
 $salesTeam = User::where('role', 'sales')->orWhere('role', 'admin')->get();
        if ($salesTeam->isNotEmpty()) {
            Notification::send($salesTeam, new NewCustomerNotification($customer));
        }

        return redirect()->route('dashboard.customers.index')->with('success', 'تم إضافة العميل بنجاح.');
    }

    public function show(Customer $customer)
    {
        $customer->load('contracts.project');
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
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
            'address' => ['nullable', 'string', 'max:255'],
            'agreement_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
        ]);

        $customer->update($validated);
        return redirect()->route('dashboard.customers.index')->with('success', 'تم تحديث بيانات العميل بنجاح.');
    }

    // ... (بقية الدوال: destroy, trash, restore, etc.)
    public function destroy(Customer $customer)
    {
        if ($customer->contracts()->exists()) {
            return back()->with('error', 'لا يمكن حذف عميل لديه عقود مرتبطة.');
        }
        $customer->delete();
        return redirect()->route('dashboard.customers.index')->with('success', 'تم نقل العميل إلى سلة المحذوفات.');
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
        if ($customer->contracts()->exists()) {
             return back()->with('error', 'لا يمكن حذف هذا العميل نهائياً لأنه مرتبط بعقود.');
        }
        $customer->forceDelete();
        return back()->with('success', 'تم حذف العميل نهائياً.');
    }
    public function exportExcel()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }
}
