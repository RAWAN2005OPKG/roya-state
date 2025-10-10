<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use Illuminate\Http\Request;
use App\Exports\InvestorsExport;
use Maatwebsite\Excel\Facades\Excel;

class InvestorController extends Controller
{

    public function index(Request $request)
    {
        $query = Investor::query();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('id_number', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortBy = ['name', 'created_at', 'id_number'];
        if (in_array($sortBy, $allowedSortBy)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $investors = $query->paginate(15);

        return view('dashboard.investors.index', [
            'investors' => $investors,
            'search' => $request->search ?? '',
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ]);
    }


    public function create()
    {
        return view('dashboard.investors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'id_number' => ['nullable', 'string', 'max:100', 'unique:investors,id_number'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255', 'unique:investors,email'],
            'jobs' => ['nullable', 'jobs', 'max:255', 'unique:investors,jobss'],

            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
        Investor::create($validated);
        return redirect()->route('dashboard.investors.index')->with('success', 'تم إضافة المستثمر بنجاح.');
    }

    public function edit(Investor $investor)
    {
        return view('dashboard.investors.edit', compact('investor'));
    }

    public function update(Request $request, Investor $investor)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'id_number' => ['nullable', 'string', 'max:100', 'unique:investors,id_number,' . $investor->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255', 'unique:investors,email,' . $investor->id],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
        $investor->update($validated);
        return redirect()->route('dashboard.investors.index')->with('success', 'تم تحديث بيانات المستثمر بنجاح.');
    }

    public function destroy(Investor $investor)
    {
        $investor->delete();
        return back()->with('success', 'تم نقل المستثمر إلى سلة المحذوفات.');
    }

    public function trash()
    {
        $trashedInvestors = Investor::onlyTrashed()->latest('deleted_at')->paginate(15);
        return view('dashboard.investors.trash', ['investors' => $trashedInvestors]);
    }

    public function restore($id)
    {
        Investor::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'تم استعادة المستثمر بنجاح.');
    }

    public function forceDelete($id)
    {
        Investor::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'تم حذف المستثمر نهائياً.');
    }

    public function exportExcel()
    {
        return Excel::download(new InvestorsExport, 'investors.xlsx');
    }
}
