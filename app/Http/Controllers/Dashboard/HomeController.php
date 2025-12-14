<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SaleInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Expense;

class HomeController extends Controller
{
    public function index()
    {
        // 1. بطاقات الإحصائيات الرئيسية
        $totalRevenue = SaleInvoice::sum('total_amount');
        $totalExpenses = Expense::sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;

        // 2. بيانات الرسم البياني (آخر 6 أشهر)
        $revenueData = [];
        $expenseData = [];
        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('F'); // اسم الشهر (e.g., November)
            $months[] = $monthName;

            // الإيرادات للشهر الحالي
            $revenueData[] = SaleInvoice::whereYear('issue_date', $date->year)
                                        ->whereMonth('issue_date', $date->month)
                                        ->sum('total_amount');

            // المصروفات للشهر الحالي
            $expenseData[] = Expense::whereYear('date', $date->year)
                                    ->whereMonth('date', $date->month)
                                    ->sum('amount');
        }

        // 3. الفواتير المتأخرة
        $overdueSalesInvoices = SaleInvoice::where('status', '!=', 'paid')
                                           ->where('due_date', '<', now())
                                           ->latest()
                                           ->take(5)
                                           ->get();

        $overduePurchaseInvoices = PurchaseInvoice::where('status', '!=', 'paid')
                                                  ->where('due_date', '<', now())
                                                  ->latest()
                                                  ->take(5)
                                                  ->get();


// سنقوم بجلب اسم العميل بدلاً من الـ ID
$latestRevenues = SaleInvoice::join('customers', 'sale_invoices.customer_id', '=', 'customers.id')
                            ->select(
                                'sale_invoices.issue_date as date',
                                'customers.name as description',
                                'sale_invoices.total_amount as amount'
                            )
                            ->selectRaw("'revenue' as type")
                            ->latest('date');

$latestExpenses = Expense::select(
                                'date',
                                'payee as description', // استخدام حقل المدفوع له
                                'amount'
                            )
                           ->selectRaw("'expense' as type")
                           ->latest('date');

// دمج الحركتين وترتيبهم وأخذ آخر 10 حركات
$latestTransactions = $latestRevenues->union($latestExpenses)
                                      ->orderBy('date', 'desc')
                                      ->take(10)
                                      ->get();


        return view('dashboard.home', compact(
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'months',
            'revenueData',
            'expenseData',
            'overdueSalesInvoices',
            'overduePurchaseInvoices',
               'latestTransactions',
 ));
    }
}


