<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Customer;
use Illuminate\Validation\Rule;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    /**
     * عرض قائمة العملاء مع عدد عقود كل عميل.
     */
public function index(Request $request)
{
    $query = Customer::query();
    $search = $request->input('search');

    $sortBy = $request->input('sort_by', 'created_at');
    $sortOrder = $request->input('sort_order', 'desc');

    if ($search) {
        $query->where('name', 'LIKE', "%{$search}%")->orWhere('phone', 'LIKE', "%{$search}%");
    }

    $customers = $query->withCount('contracts')->orderBy($sortBy, $sortOrder)->paginate(15);

    $totalClients = Customer::count();
    $totalAgreements = \App\Models\Contract::where('contractable_type', Customer::class)->sum('investment_amount');

    return view('dashboard.customers.index', compact(
        'customers',
        'totalClients',
        'totalAgreements',
        'search',
        'sortBy',
        'sortOrder'
    ));
}

    /**
     * عرض صفحة إضافة عميل جديد.
     */
    public function create()
    {
        return view('dashboard.customers.create');
    }

    /**
     * تخزين عميل جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255', 'unique:customers,email'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        Customer::create($validated);

        return redirect()->route('dashboard.customers.index')->with('success', 'تم إضافة العميل بنجاح. يمكنك الآن إنشاء عقد له.');
    }

    /**
     * عرض صفحة تفاصيل العميل مع عقوده.
     */
    public function show(Customer $customer)
    {
        // جلب العقود والمشاريع المرتبطة بكل عقد
        $customer->load('contracts.project');

        return view('dashboard.customers.show', compact('customer'));
    }

    /**
     * عرض صفحة تعديل بيانات العميل.
     */
    public function edit(Customer $customer)
    {
        return view('dashboard.customers.edit', compact('customer'));
    }

    /**
     * تحديث بيانات العميل في قاعدة البيانات.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $customer->update($validated);
        return redirect()->route('dashboard.customers.index')->with('success', 'تم تحديث بيانات العميل بنجاح.');
    }

    /**
     * نقل العميل إلى سلة المحذوفات (حذف ناعم).
     */
    public function destroy(Customer $customer)
    {
        // منع حذف عميل لديه عقود مرتبطة
        if ($customer->contracts()->exists()) {
            return back()->with('error', 'لا يمكن حذف عميل لديه عقود مرتبطة.');
        }

        $customer->delete();
        return redirect()->route('dashboard.customers.index')->with('success', 'تم نقل العميل إلى سلة المحذوفات.');
    }

    /**
     * عرض سلة المحذوفات للعملاء.
     */
    public function trash()
    {
        $trashedCustomers = Customer::onlyTrashed()->latest('deleted_at')->paginate(15);
        return view('dashboard.customers.trash', ['customers' => $trashedCustomers]);
    }

    /**
     * استعادة عميل من سلة المحذوفات.
     */
    public function restore($id)
    {
        Customer::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'تم استعادة العميل بنجاح.');
    }

    /**
     * حذف العميل نهائياً من قاعدة البيانات.
     */
    public function forceDelete($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);
        // تأكد من عدم وجود علاقات قبل الحذف النهائي
        if ($customer->contracts()->exists()) {
             return back()->with('error', 'لا يمكن حذف هذا العميل نهائياً لأنه مرتبط بعقود.');
        }
        $customer->forceDelete();
        return back()->with('success', 'تم حذف العميل نهائياً.');
    }

    /**
     * تصدير بيانات العملاء إلى ملف Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }
}
