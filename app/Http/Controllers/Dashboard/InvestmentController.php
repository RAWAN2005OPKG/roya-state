<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Investor;
use Illuminate\Http\Request;
use App\Exports\InvestmentsExport;
use Maatwebsite\Excel\Facades\Excel;

class InvestmentController extends Controller
{

   public function index(Request $request)
{
    // 1. ابدأ ببناء استعلام (Query) مع جلب العلاقة
    $query = Investment::with('investor');

    // 2. تطبيق البحث
    if ($request->has('search') && $request->search != '') {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('project', 'LIKE', "%{$searchTerm}%")
              ->orWhere('type', 'LIKE', "%{$searchTerm}%")
              // البحث في الجدول المرتبط (investors)
              ->orWhereHas('investor', function ($subQuery) use ($searchTerm) {
                  $subQuery->where('name', 'LIKE', "%{$searchTerm}%");
              });
        });
    }

    $sortBy = $request->get('sort_by', 'date');
    $sortOrder = $request->get('sort_order', 'desc');
    $allowedSortBy = ['date', 'project', 'amount', 'share_percentage'];
    if (in_array($sortBy, $allowedSortBy)) {
        $query->orderBy($sortBy, $sortOrder);
    }

    $investments = $query->paginate(15);

    return view('dashboard.investments.index', [
        'investments' => $investments,
        'search' => $request->search ?? '',
        'sort_by' => $sortBy,
        'sort_order' => $sortOrder,
    ]);
}


    public function create()
    {
        $investors = Investor::all();
        return view('dashboard.investments.create', compact('investors'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'investor_id' => ['required', 'exists:investors,id'],
            'date' => ['required', 'date'],
            'project' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:10'],
            'share_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'status' => ['nullable', 'in:active,completed,cancelled'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'payee' => ['nullable', 'string', 'max:255'],
            'payment_date' => ['nullable', 'date'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'other_bank_name' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'contract_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        Investment::create($validated);

        return redirect()->route('dashboard.investments.index')->with('success', 'تم إضافة الاستثمار بنجاح.');
    }


    public function edit(Investment $investment)
    {
        $investors = Investor::all();
        return view('dashboard.investments.edit', compact('investment', 'investors'));
    }


    public function update(Request $request, Investment $investment)
    {
        $validated = $request->validate([
            'investor_id' => ['required', 'exists:investors,id'],
            'date' => ['required', 'date'],
            'project' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:10'],
            'share_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'status' => ['nullable', 'in:active,completed,cancelled'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'payee' => ['nullable', 'string', 'max:255'],
            'payment_date' => ['nullable', 'date'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'other_bank_name' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'contract_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $investment->update($validated);

        return redirect()->route('dashboard.investments.index')->with('success', 'تم تحديث الاستثمار بنجاح.');
    }

    public function destroy(Investment $investment)
    {
        $investment->delete();
        return back()->with('success', 'تم نقل الاستثمار إلى سلة المحذوفات.');
    }

    public function trash()
    {
        $trashedInvestments = Investment::with('investor')->onlyTrashed()->latest('deleted_at')->get();
        return view('dashboard.investments.trash', ['investments' => $trashedInvestments]);
    }

    public function restore($id)
    {
        $investment = Investment::withTrashed()->findOrFail($id);
        $investment->restore();
        return back()->with('success', 'تم استعادة الاستثمار بنجاح.');
    }

    public function forceDelete($id)
    {
        $investment = Investment::withTrashed()->findOrFail($id);
        $investment->forceDelete();
        return back()->with('success', 'تم حذف الاستثمار نهائياً.');
    }

    public function exportExcel()
    {
        return Excel::download(new InvestmentsExport, 'investments.xlsx');
    }
}
