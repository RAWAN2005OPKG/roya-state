<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CashSafe; // تأكد من أن المودل موجود
use Illuminate\Http\Request;

class CashSafeController extends Controller
{
    /**
     * عرض صفحة الخزائن الرئيسية مع البحث.
     */
    public function index(Request $request)
    {
        $query = CashSafe::query();

        // تطبيق البحث
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $safes = $query->latest()->paginate(15);

        // حساب الإحصائيات
        $totalSafes = CashSafe::count();
        $activeSafes = CashSafe::where('is_active', true)->count();

        return view('dashboard.cash_safes.index', compact('safes', 'totalSafes', 'activeSafes'));
    }

    /**
     * تخزين خزينة جديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:cash_safes,name',
            'initial_balance' => 'required|numeric|min:0',
        ]);

        CashSafe::create([
            'name' => $request->name,
            'balance' => $request->initial_balance, // الرصيد الحالي هو نفسه الرصيد الافتتاحي عند الإنشاء
            'is_active' => true, // تفعيل الخزينة تلقائياً
        ]);

        return redirect()->route('dashboard.cash-safes.index')->with('success', 'تمت إضافة الخزينة بنجاح.');
    }

    /**
     * تحديث بيانات خزينة موجودة.
     */
    public function update(Request $request, CashSafe $cashSafe)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:cash_safes,name,' . $cashSafe->id,
            'balance' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $cashSafe->update($request->all());

        return redirect()->route('dashboard.cash-safes.index')->with('success', 'تم تحديث الخزينة بنجاح.');
    }

    /**
     * حذف خزينة (حذف مؤقت).
     */
    public function destroy(CashSafe $cashSafe)
    {
        // يمكنك إضافة منطق هنا لمنع حذف خزينة بها رصيد
        if ($cashSafe->balance > 0) {
            return back()->with('error', 'لا يمكن حذف خزينة تحتوي على رصيد.');
        }

        $cashSafe->delete();

        return redirect()->route('dashboard.cash-safes.index')->with('success', 'تم نقل الخزينة إلى سلة المحذوفات.');
    }

    /**
     * عرض صفحة سلة المحذوفات.
     */
    public function trash()
    {
        $trashedSafes = CashSafe::onlyTrashed()->latest()->paginate(15);
        return view('dashboard.cash_safes.trash', compact('trashedSafes'));
    }

    /**
     * استعادة خزينة محذوفة.
     */
    public function restore($id)
    {
        $safe = CashSafe::onlyTrashed()->findOrFail($id);
        $safe->restore();

        return redirect()->route('dashboard.cash-safes.trash.index')->with('success', 'تم استعادة الخزينة بنجاح.');
    }

    /**
     * حذف خزينة بشكل نهائي من قاعدة البيانات.
     */
    public function forceDelete($id)
    {
        $safe = CashSafe::onlyTrashed()->findOrFail($id);
        $safe->forceDelete();

        return redirect()->route('dashboard.cash-safes.trash.index')->with('success', 'تم حذف الخزينة نهائياً.');
    }
}
