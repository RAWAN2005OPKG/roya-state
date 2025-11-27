<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = BankAccount::query();

        if ($search = $request->input('search')) {
            $query->where('bank_name', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%");
        }

        $bankAccounts = $query->latest()->paginate(15);
        $totalAccounts = BankAccount::count();
        $activeAccounts = BankAccount::where('is_active', true)->count();

        return view('dashboard.bank_accounts.index', compact('bankAccounts', 'totalAccounts', 'activeAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:bank_accounts,account_number',
            'iban' => 'nullable|string|max:255|unique:bank_accounts,iban',
            'balance' => 'required|numeric|min:0',
        ]);

        BankAccount::create($request->all());

        return redirect()->route('dashboard.bank-accounts.index')->with('success', 'تمت إضافة الحساب البنكي بنجاح.');
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:bank_accounts,account_number,' . $bankAccount->id,
            'iban' => 'nullable|string|max:255|unique:bank_accounts,iban,' . $bankAccount->id,
            'balance' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $bankAccount->update($request->all());

        return redirect()->route('dashboard.bank-accounts.index')->with('success', 'تم تحديث الحساب البنكي بنجاح.');
    }

    public function destroy(BankAccount $bankAccount)
    {
        if ($bankAccount->balance > 0) {
            return back()->with('error', 'لا يمكن حذف حساب بنكي يحتوي على رصيد.');
        }
        $bankAccount->delete();
        return redirect()->route('dashboard.bank-accounts.index')->with('success', 'تم نقل الحساب إلى سلة المحذوفات.');
    }

    public function trash()
    {
        $trashedAccounts = BankAccount::onlyTrashed()->latest()->paginate(15);
        return view('dashboard.bank_accounts.trash', compact('trashedAccounts'));
    }

    public function restore($id)
    {
        BankAccount::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('dashboard.bank-accounts.trash.index')->with('success', 'تم استعادة الحساب بنجاح.');
    }

    public function forceDelete($id)
    {
        BankAccount::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('dashboard.bank-accounts.trash.index')->with('success', 'تم حذف الحساب نهائياً.');
    }
}
