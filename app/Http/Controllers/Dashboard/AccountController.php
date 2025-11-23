<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        // جلب الحسابات الرئيسية فقط (التي ليس لها أب) لبدء بناء الشجرة
        $accounts = Account::whereNull('parent_id')->with('children')->get();
        $allAccounts = Account::orderBy('name')->get(); // لإستخدامها في قائمة الآباء
        return view('dashboard.accounts.index', compact('accounts', 'allAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:accounts,code',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:accounts,id',
        ]);

        Account::create($request->all());
        return back()->with('success', 'تمت إضافة الحساب بنجاح.');
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:accounts,code,' . $account->id,
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'is_active' => 'required|boolean',
        ]);

        $account->update($request->all());
        return back()->with('success', 'تم تحديث الحساب بنجاح.');
    }
}
