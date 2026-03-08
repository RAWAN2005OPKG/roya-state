<?php

namespace App\Services;

// 1. استيراد كل الموديلات التي ستدخل في الحسابات
use App\Models\Setting;
use App\Models\Payment;
use App\Models\KhaledVoucher;
use App\Models\MohammedVoucher;
use App\Models\WaliVoucher;
// يمكنك إضافة أي موديلات مالية أخرى هنا في المستقبل

class FinancialService
{
    /**
     * يحسب الرصيد الحالي بناءً على كل الحركات المالية في النظام (باستثناء الكاش).
     *
     * هذه الدالة هي المحرك المالي الرئيسي لتطبيقك.
     *
     * @return float - الرصيد النهائي المحسوب.
     */
    public function getCurrentBalance(): float
    {
        // الخطوة 1: نبدأ بالرصيد الافتتاحي من جدول الإعدادات
        // هذا هو الرقم المبدئي الذي يبدأ منه كل شيء.
        $initialBudgetSetting = Setting::where('key', 'total_budget')->first();
        $balance = $initialBudgetSetting ? (float) $initialBudgetSetting->value : 0;

        // الخطوة 2: إضافة كل الإيرادات (سندات القبض والقيود الدائنة)
        // نجمع قيمة كل الحركات التي تزيد من الرصيد.
        // ملاحظة: نستخدم حقل 'amount_ils' لتوحيد العملة وضمان دقة الحسابات.
        $balance += Payment::where('type', 'in')->sum('amount_ils');
        $balance += KhaledVoucher::where('type', 'receipt')->sum('amount'); // افترضنا أن هذه المبالغ بالعملة الأساسية
        $balance += MohammedVoucher::where('type', 'receipt')->sum('amount');
        $balance += WaliVoucher::where('type', 'receipt')->sum('amount');

        // الخطوة 3: طرح كل المصروفات (سندات الصرف والقيود المدينة)
        // نطرح قيمة كل الحركات التي تنقص من الرصيد.
        $balance -= Payment::where('type', 'out')->sum('amount_ils');
        $balance -= KhaledVoucher::where('type', 'payment')->sum('amount');
        $balance -= MohammedVoucher::where('type', 'payment')->sum('amount');
        $balance -= WaliVoucher::where('type', 'payment')->sum('amount');

        // الخطوة 4: إرجاع الرصيد النهائي المحسوب
        return $balance;
    }

    /**
     * دالة مثال: يمكنك إضافة دوال حسابية أخرى هنا في المستقبل بسهولة.
     * على سبيل المثال، حساب إجمالي الإيرادات لهذا الشهر.
     *
     * @return float
     */
    public function getTotalRevenueThisMonth(): float
    {
        $totalRevenue = 0;
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $totalRevenue += Payment::where('type', 'in')
                                ->whereYear('payment_date', $currentYear)
                                ->whereMonth('payment_date', $currentMonth)
                                ->sum('amount_ils');

        // يمكنك إضافة باقي الموديلات بنفس الطريقة...

        return $totalRevenue;
    }
}
