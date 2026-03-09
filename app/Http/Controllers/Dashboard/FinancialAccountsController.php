<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\CashSafe;
use App\Models\Check;
use Illuminate\Http\Request;

class FinancialAccountsController extends Controller
{
    public function index(Request $request)
    {
        // جلب بيانات الخزائن
        $cashSafes = CashSafe::latest()->get();

        // جلب بيانات الحسابات البنكية
        $bankAccounts = BankAccount::latest()->get();

        // جلب بيانات الشيكات مع الفلاتر
        $checksQuery = Check::latest();

        if ($request->filled('check_status')) {
            $checksQuery->where('status', $request->check_status);
        }
        if ($request->filled('check_type')) {
            $checksQuery->where('type', $request->check_type);
        }
        if ($request->filled('check_search')) {
            $searchTerm = $request->check_search;
            $checksQuery->where(function ($query) use ($searchTerm) {
                $query->where('check_number', 'like', "%{$searchTerm}%")
                      ->orWhere('holder_name', 'like', "%{$searchTerm}%");
            });
        }
        $checks = $checksQuery->paginate(10, ['*'], 'checks_page');

        // حساب الإجماليات باستخدام FinancialService لضمان الدقة
        $financialService = new \App\Services\FinancialService();
        $totalCashBalance = $financialService->getCashBalance();
        $totalBankBalance = $financialService->getBankBalance();
        $totalOverallBalance = $financialService->getTotalCapital();

        return view('dashboard.financial_accounts.index', compact(
            'cashSafes',
            'bankAccounts',
            'checks',
            'totalCashBalance',
            'totalBankBalance',
            'totalOverallBalance'
        ));
    }
}
