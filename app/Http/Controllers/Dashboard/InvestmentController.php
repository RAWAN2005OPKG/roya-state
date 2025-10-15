<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Investor;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Exports\InvestmentsExport;
use Maatwebsite\Excel\Facades\Excel;

class InvestmentController extends Controller
{
    // عرض جميع الاستثمارات مع البحث والفلترة
    public function index(Request $request)
    {
        $query = Investment::with('investor');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('project', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('type', 'LIKE', "%{$searchTerm}%")
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

    // صفحة إنشاء استثمار جديد
    public function create()
    {
        $investors = Investor::all();
        $projects = Project::all();

        return view('dashboard.investments.create', compact('investors', 'projects'));
    }

    // حفظ استثمار جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'investor_id' => ['required', 'integer', 'exists:investors,id'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => ['string', 'max:255'],
            'down_payment_other' => ['nullable', 'numeric', 'min:0'],
            'first_payment_date' => ['nullable', 'date'],
            'remaining_amount' => ['nullable', 'numeric', 'min:0'],
            'cash_receiver' => ['nullable', 'string', 'max:255'],
            'cash_receiver_job' => ['nullable', 'string', 'max:255'],
            'cash_receipt_date' => ['nullable', 'date'],
            'sender_bank' => ['nullable', 'string', 'max:255'],
            'receiver_bank' => ['nullable', 'string', 'max:255'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['nullable', 'date'],
            'check_number' => ['nullable', 'string', 'max:255'],
            'check_owner' => ['nullable', 'string', 'max:255'],
            'check_bank' => ['nullable', 'string', 'max:255'],
            'check_due_date' => ['nullable', 'date'],
        ]);

        $validated['payment_method'] = implode(',', $request->input('payment_methods', []));

        Investment::create($validated);

        return redirect()->route('dashboard.investments.index')
                         ->with('success', 'تم إضافة الاستثمار بنجاح.');
    }

    // صفحة تعديل الاستثمار
    public function edit(Investment $investment)
    {
        $investors = Investor::all();
        $projects = Project::all();

        return view('dashboard.investments.edit', compact('investment', 'investors', 'projects'));
    }

    // تحديث بيانات الاستثمار
    public function update(Request $request, Investment $investment)
    {
        $validated = $request->validate([
            'investor_id' => ['required', 'integer', 'exists:investors,id'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => ['string', 'max:255'],
            'down_payment_other' => ['nullable', 'numeric', 'min:0'],
            'first_payment_date' => ['nullable', 'date'],
            'remaining_amount' => ['nullable', 'numeric', 'min:0'],
            'cash_receiver' => ['nullable', 'string', 'max:255'],
            'cash_receiver_job' => ['nullable', 'string', 'max:255'],
            'cash_receipt_date' => ['nullable', 'date'],
            'sender_bank' => ['nullable', 'string', 'max:255'],
            'receiver_bank' => ['nullable', 'string', 'max:255'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['nullable', 'date'],
            'check_number' => ['nullable', 'string', 'max:255'],
            'check_owner' => ['nullable', 'string', 'max:255'],
            'check_bank' => ['nullable', 'string', 'max:255'],
            'check_due_date' => ['nullable', 'date'],
        ]);

        $validated['payment_method'] = implode(',', $request->input('payment_methods', []));

        $investment->update($validated);

        return redirect()->route('dashboard.investments.index')
                         ->with('success', 'تم تحديث الاستثمار بنجاح.');
    }

    // حذف الاستثمار (نقله للسلة)
    public function destroy(Investment $investment)
    {
        $investment->delete();
        return back()->with('success', 'تم نقل الاستثمار إلى سلة المحذوفات.');
    }

    // عرض الاستثمارات المحذوفة
    public function trash()
    {
        $trashedInvestments = Investment::with('investor')
                                       ->onlyTrashed()
                                       ->latest('deleted_at')
                                       ->get();

        return view('dashboard.investments.trash', compact('trashedInvestments'));
    }

    // استعادة الاستثمار من سلة المحذوفات
    public function restore($id)
    {
        $investment = Investment::withTrashed()->findOrFail($id);
        $investment->restore();

        return back()->with('success', 'تم استعادة الاستثمار بنجاح.');
    }

    // حذف نهائي للاستثمار
    public function forceDelete($id)
    {
        $investment = Investment::withTrashed()->findOrFail($id);
        $investment->forceDelete();

        return back()->with('success', 'تم حذف الاستثمار نهائياً.');
    }

    // تصدير البيانات إلى Excel
    public function exportExcel()
    {
        return Excel::download(new InvestmentsExport, 'investments.xlsx');
    }
}
