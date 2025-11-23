<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SaleInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        
        // إجمالي الإيرادات: هو مجموع المبالغ **المدفوعة فعلياً** من فواتير المبيعات.
        $totalRevenue = SaleInvoice::sum('paid_amount');

        // إجمالي المصروفات: هو مجموع كل المصروفات المسجلة في جدول المصروفات.
        $totalExpenses = Expense::sum('amount');

        // صافي الربح / الخسارة: هو إجمالي الإيرادات (المبالغ المدفوعة) ناقص إجمالي المصروفات.
        $netProfit = $totalRevenue - $totalExpenses;

        // صافي التدفق النقدي: هو مجموع كل الأموال التي دخلت (من المبيعات) ناقص كل الأموال التي خرجت (للمشتريات والمصروفات).
        $cashIn = SaleInvoice::sum('paid_amount');
        $cashOut = PurchaseInvoice::sum('paid_amount') + Expense::sum('amount');
        $netCashFlow = $cashIn - $cashOut;


        // ===================================================================
        // 2. بيانات الرسم البياني (آخر 6 أشهر) - بيانات حقيقية
        // ===================================================================
        $chartData = $this->getRealChartData();


        // ===================================================================
        // 3. تصنيفات المصروفات - بيانات حقيقية
        // ===================================================================
        // هذا الاستعلام يجمع المصروفات حسب الفئة ويعرض أعلى 5 فئات إنفاقاً.
        // يفترض أن لديك علاقة `category` في موديل `Expense`.
        $expenseCategories = Expense::select('category_id', DB::raw('SUM(amount) as total'))
                                    ->with('category')
                                    ->groupBy('category_id')
                                    ->orderBy('total', 'desc')
                                    ->limit(5)
                                    ->get();


        // ===================================================================
        // 4. فواتير المبيعات المتأخرة - بيانات حقيقية
        // ===================================================================
        // أولاً، نقوم بتحديث حالة الفواتير التي أصبحت متأخرة.
        SaleInvoice::where('status', '!=', 'paid')
                   ->whereDate('due_date', '<', now())
                   ->update(['status' => 'overdue']);

        // ثانياً، نجلب أحدث 5 فواتير متأخرة لعرضها.
        $overdueSales = SaleInvoice::with('customer')
                                   ->where('status', 'overdue')
                                   ->latest('due_date')
                                   ->limit(5)
                                   ->get();


        // ===================================================================
        // 5. فواتير المشتريات المستحقة - بيانات حقيقية
        // ===================================================================
        // نجلب أقدم 5 فواتير مشتريات لم يتم دفعها بالكامل وتاريخ استحقاقها قريب.
        $duePurchases = PurchaseInvoice::with('supplier')
                                       ->whereIn('status', ['unpaid', 'partial'])
                                       ->whereDate('due_date', '<=', now()->addDays(7)) // المستحقة خلال 7 أيام
                                       ->orderBy('due_date', 'asc') // نعرض الأقدم أولاً
                                       ->limit(5)
                                       ->get();


        // ===================================================================
        // تمرير جميع البيانات الحقيقية إلى الواجهة
        // ===================================================================
        return view('dashboard.index', compact(
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'netCashFlow',
            'chartData',
            'expenseCategories',
            'overdueSales',
            'duePurchases'
        ));
    }

    /**
     * دالة خاصة لجلب بيانات الرسم البياني الحقيقية لآخر 6 أشهر.
     */
    private function getRealChartData()
    {
        $labels = [];
        $revenuesData = [];
        $expensesData = [];

        // حلقة تكرارية لآخر 6 أشهر، بدءاً من الشهر الحالي والعودة للخلف.
        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths($i);
            // إضافة اسم الشهر المترجم إلى مصفوفة العناوين
            $labels[] = $month->translatedFormat('F'); // مثال: "نوفمبر", "أكتوبر", ...

            // حساب الإيرادات (المبالغ المدفوعة) للشهر المحدد.
            $revenuesData[] = SaleInvoice::whereYear('updated_at', $month->year) // نستخدم updated_at لتتبع تاريخ الدفع
                                         ->whereMonth('updated_at', $month->month)
                                         ->sum('paid_amount');

            // حساب المصروفات للشهر المحدد.
            $expensesData[] = Expense::whereYear('date', $month->year)
                                     ->whereMonth('date', $month->month)
                                     ->sum('amount');
        }

        // يجب عكس المصفوفات لأننا بدأنا من الشهر الحالي ورجعنا للخلف.
        return [
            'labels' => json_encode(array_reverse($labels)),
            'revenues' => json_encode(array_reverse($revenuesData)),
            'expenses' => json_encode(array_reverse($expensesData)),
        ];
    }
}
