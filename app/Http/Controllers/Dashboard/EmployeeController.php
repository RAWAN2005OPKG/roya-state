<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Exports\EmployeesExport; // تأكد من إنشاء هذا الملف لاحقًا
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('position', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
        }

        $employees = $query->orderBy($sortBy, $sortOrder)->paginate(15);

        return view('dashboard.employees.index', compact('employees', 'search', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        return view('dashboard.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateEmployee($request);
        Employee::create($validated);
        return redirect()->route('dashboard.employees.index')->with('success', 'تم إضافة الموظف بنجاح.');
    }

    public function edit(Employee $employee)
    {
        return view('dashboard.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validateEmployee($request, $employee->id);
        $employee->update($validated);
        return redirect()->route('dashboard.employees.index')->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return back()->with('success', 'تم نقل الموظف إلى سلة المحذوفات.');
    }

    public function trash()
    {
        $trashedEmployees = Employee::onlyTrashed()->paginate(15);
        return view('dashboard.employees.trash', ['employees' => $trashedEmployees]);
    }

    public function restore($id)
    {
        Employee::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'تم استعادة الموظف بنجاح.');
    }

    public function forceDelete($id)
    {
        Employee::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'تم حذف الموظف نهائياً.');
    }

    public function exportExcel()
    {
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }

    private function validateEmployee(Request $request, $employeeId = null)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:employees,email,' . $employeeId],
            'phone' => ['nullable', 'string', 'max:50'],
            'iban' => ['nullable', 'string', 'max:255'],
            'salary' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'wallet_name' => ['nullable', 'string', 'max:100'],
            'wallet_other_name' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'other_bank_name' => ['nullable', 'string', 'max:100'],
            'bank_branch' => ['nullable', 'string', 'max:100'],
        ];

        return $request->validate($rules);
    }
}
