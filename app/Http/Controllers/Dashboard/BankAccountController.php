<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * عرض قائمة بكل الحسابات البنكية
     */
    public function index()
    {
        // جلب الحسابات مع اسم البنك المرتبط بها بكفاءة (Eager Loading)
        $bankAccounts = BankAccount::with('bank')->latest()->paginate(15);

        // إحصائيات للصفحة
        $totalAccounts = BankAccount::count();
        $activeAccounts = BankAccount::where('is_active', true)->count();

        return view('dashboard.bank-accounts.index', compact(
            'bankAccounts',
            'totalAccounts',
            'activeAccounts'
        ));
    }

    /**
     * عرض نموذج إضافة حساب بنكي جديد
     */
    public function create()
    {
        // جلب البنوك النشطة لعرضها في القائمة المنسدلة
        $banks = Bank::where('is_active', true)->orderBy('name')->get();

        if ($banks->isEmpty()) {
            return redirect()->route('dashboard.banks.index')
                ->with('error', 'يجب عليك إضافة بنك واحد على الأقل قبل إضافة حساب بنكي.');
        }

        return view('dashboard.bank-accounts.create', compact('banks'));
    }

    /**
     * تخزين حساب بنكي جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات، مع التأكد من وجود 'bank_id'
        $validated = $request->validate([
            'bank_id' => 'required|exists:banks,id', // <-- التصحيح الأهم: استخدام bank_id
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:bank_accounts,account_number',
            'iban' => 'nullable|string|max:255|unique:bank_accounts,iban',
            'currency' => 'required|string|max:10',
            'current_balance' => 'nullable|numeric|min:0', // اسم الحقل الصحيح
        ]);

        BankAccount::create($validated);

        return redirect()->route('dashboard.bank-accounts.index')->with('success', 'تم إضافة الحساب البنكي بنجاح.');
    }

    /**
     * عرض نموذج تعديل حساب بنكي
     */
    public function edit(BankAccount $bankAccount)
    {
        // جلب كل البنوك النشطة للسماح بتغيير البنك
        $banks = Bank::where('is_active', true)->orderBy('name')->get();

        return view('dashboard.bank-accounts.edit', compact('bankAccount', 'banks'));
    }

    /**
     * تحديث بيانات حساب بنكي في قاعدة البيانات
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $validated = $request->validate([
            'bank_id' => 'required|exists:banks,id', // <-- التصحيح الأهم: استخدام bank_id
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:bank_accounts,account_number,' . $bankAccount->id,
            'iban' => 'nullable|string|max:255|unique:bank_accounts,iban,' . $bankAccount->id,
            'currency' => 'required|string|max:10',
            'is_active' => 'required|boolean',
        ]);

        $bankAccount->update($validated);

        return redirect()->route('dashboard.bank-accounts.index')->with('success', 'تم تعديل الحساب البنكي بنجاح.');
    }

    /**
     * حذف حساب بنكي من قاعدة البيانات
     */
    public function destroy(BankAccount $bankAccount)
    {
        // يمكنك إضافة شرط هنا لمنع حذف حساب يحتوي على حركات أو رصيد
        if ($bankAccount->transactions()->exists() || $bankAccount->current_balance > 0) {
            return back()->with('error', 'لا يمكن حذف حساب بنكي يحتوي على حركات أو رصيد.');
        }

        $bankAccount->delete();

        return redirect()->route('dashboard.bank-accounts.index')->with('success', 'تم حذف الحساب البنكي بنجاح.');
    }
}
