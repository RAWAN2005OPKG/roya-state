<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SaleInvoice;
use App\Models\Expense;
use App\Models\JournalEntry;

class FinancialController extends Controller
{
    public function summary()
    {
        // 1. بطاقات الإحصائيات الرئيسية
        $totalRevenue = SaleInvoice::sum('total_amount');
        $totalExpenses = Expense::sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;
        // صافي التدفق النقدي يتطلب حسابات أكثر تعقيداً (إيداعات - سحوبات)، سنتركه 0 مؤقتاً
        $netCashFlow = 0;

        // 2. بيانات الرسم البياني (آخر 6 أشهر)
        $chartData = [
            'revenue' => [],
            'expense' => []
        ];
        $chartLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $date->format('F'); // اسم الشهر (e.g., November)

            $chartData['revenue'][] = SaleInvoice::whereYear('issue_date', $date->year)
                                                ->whereMonth('issue_date', $date->month)
                                                ->sum('total_amount') ?? 0;

            $chartData['expense'][] = Expense::whereYear('date', $date->year)
                                              ->whereMonth('date', $date->month)
                                              ->sum('amount') ?? 0;
        }

        // 3. أحدث المعاملات (من قيود اليومية)
        $latestTransactions = JournalEntry::with('items')->latest()->take(5)->get();

        // إرسال كل البيانات إلى الواجهة
        return view('dashboard.financial.summary', compact(
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'netCashFlow',
            'chartLabels',
            'chartData',
            'latestTransactions'
        ));
    }
}
