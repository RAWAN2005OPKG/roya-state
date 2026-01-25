<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GeneralContract; // عقود الموردين والمستثمرين
use App\Models\Contract; // عقود العملاء
use App\Models\SupplierPayment; // مصروفات الموردين
use App\Models\Setting; // الإعدادات
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. جلب الرصيد الافتتاحي من جدول الإعدادات
        $openingBalance = (float) Setting::where('key', 'opening_balance')->value('value');

        // 2. حساب الإيرادات (مثال: من عقود العملاء)
        $totalRevenue = Contract::sum(DB::raw('contract_value * exchange_rate'));

        // 3. حساب المصروفات (مثال: من مصروفات الموردين)
        $totalExpenses = SupplierPayment::sum('amount');

        // 4. حساب السيولة الحالية
        $currentCash = $openingBalance + $totalRevenue - $totalExpenses;

        // 5. بيانات الرسم البياني (يمكن تحسينها لاحقاً)
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
