<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClientPayment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnnualReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. تحديد السنوات
        $selectedYear = (int) ($request->query('year', now()->year));
        $previousYear = $selectedYear - 1;
        $years = [$selectedYear, $previousYear];

        // 2. جلب الإيرادات الشهرية باستخدام Eloquent Model
        $monthlyRevenue = ClientPayment::query()
            ->whereIn(DB::raw('YEAR(date)'), $years)
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as total_revenue')
            )
            ->groupBy('year', 'month')
            ->get();

        // 3. جلب المصروفات الشهرية باستخدام Eloquent Model
        $monthlyExpenses = Expense::query()
            ->whereIn(DB::raw('YEAR(date)'), $years)
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as total_expenses')
            )
            ->groupBy('year', 'month')
            ->get();

        // 4. دمج البيانات في هيكل واحد سهل الاستخدام
        $reportData = [];
        for ($m = 1; $m <= 12; $m++) {
            # صافي ربح السنة الحالية
            $revenueCurrent = $monthlyRevenue->where('year', $selectedYear)->where('month', $m)->first()->total_revenue ?? 0;
            $expensesCurrent = $monthlyExpenses->where('year', $selectedYear)->where('month', $m)->first()->total_expenses ?? 0;

            # صافي ربح السنة السابقة
            $revenuePrevious = $monthlyRevenue->where('year', $previousYear)->where('month', $m)->first()->total_revenue ?? 0;
            $expensesPrevious = $monthlyExpenses->where('year', $previousYear)->where('month', $m)->first()->total_expenses ?? 0;

            $reportData[] = [
                'month' => $m,
                'month_name' => Carbon::create()->month($m)->translatedFormat('F'),
                'net_profit_current' => $revenueCurrent - $expensesCurrent,
                'net_profit_previous' => $revenuePrevious - $expensesPrevious,
            ];
        }

        return view('dashboard.years', [
            'selectedYear' => $selectedYear,
            'previousYear' => $previousYear,
            'reportData' => $reportData,
        ]);
    }
}
