<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GeneralContract;
use App\Models\Contract;
use App\Models\SupplierPayment;
use App\Models\Setting;
use App\Models\Cheque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. جلب الرصيد الافتتاحي
        $openingBalance = (float) Setting::where('key', 'opening_balance')->value('value');

        // 2. حساب الإيرادات (عقود العملاء + الشيكات المحصلة)
        $revenueFromContracts = Contract::sum(DB::raw('contract_value * exchange_rate'));
        $revenueFromCheques = Cheque::where('type', 'inbound')->where('status', 'collected')->sum('amount');
        $totalRevenue = $revenueFromContracts + $revenueFromCheques;

        // 3. حساب المصروفات (مصروفات الموردين + الشيكات الصادرة المصروفة)
        $expensesFromPayments = SupplierPayment::sum('amount');
        $expensesFromCheques = Cheque::where('type', 'outbound')->where('status', 'collected')->sum('amount');
        $totalExpenses = $expensesFromPayments + $expensesFromCheques;

        // 4. حساب السيولة الحالية
        $currentCash = $openingBalance + $totalRevenue - $totalExpenses;

        // 5. بيانات الرسم البياني (مثال بسيط، يمكن تحسينه)
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $revenueData = [1200, 1900, 3000, 5000, 2000, 3000];
        $expenseData = [800, 1200, 1500, 2500, 1800, 2000];

        return view('dashboard.index', [
            'openingBalance' => $openingBalance,
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'currentCash' => $currentCash,
            'months' => $months,
            'revenueData' => $revenueData,
            'expenseData' => $expenseData,
        ]);
    }
}
