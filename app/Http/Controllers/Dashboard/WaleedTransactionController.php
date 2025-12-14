<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\WaleedTransaction;
use Illuminate\Http\Request;

class WaleedTransactionController extends Controller
{
    public function index()
    {
        $transactions = WaleedTransaction::latest()->paginate(15);
        return view('dashboard.waleed_transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('dashboard.waleed_transactions.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'amount_shekel' => 'nullable|numeric',
            'amount_dollar' => 'nullable|numeric',
            'paid_by' => 'required|string|max:255',
            'paid_to' => 'required|string|max:255',
            'expense_details' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        WaleedTransaction::create($validatedData);

        return redirect()->route('dashboard.waleed-transactions.index')
                         ->with('success', 'تم تسجيل الحركة بنجاح.');
    }

    // >>== دالة عرض صفحة التعديل (جديدة) ==<<
    public function edit(WaleedTransaction $waleedTransaction)
    {
        // اسم المتغير $waleedTransaction يأتي من اسم المودل
        return view('dashboard.waleed_transactions.edit', compact('waleedTransaction'));
    }

    // >>== دالة تحديث البيانات (جديدة) ==<<
    public function update(Request $request, WaleedTransaction $waleedTransaction)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'amount_shekel' => 'nullable|numeric',
            'amount_dollar' => 'nullable|numeric',
            'paid_by' => 'required|string|max:255',
            'paid_to' => 'required|string|max:255',
            'expense_details' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $waleedTransaction->update($validatedData);

        return redirect()->route('dashboard.waleed-transactions.index')
                         ->with('success', 'تم تعديل الحركة بنجاح.');
    }

    // >>== دالة الحذف (جديدة) ==<<
    public function destroy(WaleedTransaction $waleedTransaction)
    {
        $waleedTransaction->delete();

        return redirect()->route('dashboard.waleed-transactions.index')
                         ->with('success', 'تم حذف الحركة بنجاح.');
    }
}
