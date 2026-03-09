<?php

namespace App\Services;

// 1. استيراد كل الموديلات التي ستدخل في الحسابات
use App\Models\Setting;
use App\Models\CashTransaction;
use App\Models\BankAccount;
use App\Models\Project;
use App\Models\Expense;
use App\Models\SupplierExpense; // **تمت إضافته**
use App\Models\Check;            // **تمت إضافته**
use App\Models\Payment;
use App\Models\KhaledVoucher;
use App\Models\MohammedVoucher;
use App\Models\WaliVoucher;

class FinancialService
{
    /**
     * يحسب إجمالي رأس المال (السيولة) في النظام.
     * هو مجموع رصيد الخزينة + مجموع أرصدة كل البنوك + قيمة الشيكات قيد التحصيل.
     *
     * @return float
     */
    public function getTotalCapital(): float
    {
        $cashBalance = $this->getCashBalance();
        $bankBalance = $this->getBankBalance();
        $checksInWallet = $this->getChecksInWalletValue(); // **تمت إضافته**

        // إجمالي السيولة هو ما تملكه نقداً وفي البنوك، بالإضافة إلى الشيكات التي في محفظتك
        return $cashBalance + $bankBalance + $checksInWallet;
    }

    /**
     * يحسب الرصيد الحالي للخزينة (الكاش) فقط.
     *
     * @return float
     */
    public function getCashBalance(): float
    {
        $openingBalance = (float) Setting::where('key', 'opening_balance')->value('value');
        $totalIn = CashTransaction::where('type', 'in')->sum('amount_ils');
        $totalOut = CashTransaction::where('type', 'out')->sum('amount_ils');

        return $openingBalance + $totalIn - $totalOut;
    }

    /**
     * يحسب مجموع الأرصدة الحالية في كل الحسابات البنكية.
     *
     * @return float
     */
    public function getBankBalance(): float
    {
        return (float) BankAccount::where('is_active', true)->sum('balance');
    }

    /**
     * **دالة جديدة ومهمة جداً**
     * تحسب القيمة الإجمالية للشيكات المستحقة (شيكات القبض) التي لم يتم تحصيلها بعد.
     *
     * @return float
     */
    public function getChecksInWalletValue(): float
    {
        // 'receivable' = شيكات قبض
        // 'in_wallet' = في المحفظة (لم تودع في البنك بعد)
        // 'under_collection' = أودعت في البنك وتنتظر التحصيل
        return (float) Check::where('type', 'receivable')
                            ->whereIn('status', ['in_wallet', 'under_collection'])
                            ->sum('amount_ils');
    }

    /**
     * يحسب إجمالي المصروفات المسجلة في النظام (العامة + الموردين).
     *
     * @return float
     */
    public function getTotalExpenses(): float
    {
        $generalExpenses = (float) Expense::sum('amount_ils');
        $supplierExpenses = (float) SupplierExpense::sum('total_amount'); // نفترض أن هذا الحقل بالشيكل

        return $generalExpenses + $supplierExpenses;
    }

    /**
     * يحسب إجمالي الأرصدة المستثمرة في كل المشاريع.
     *
     * @return float
     */
    public function getTotalProjectsBalance(): float
    {
        return (float) Project::sum('balance');
    }


    // ===================================================================
    // == الدالة القديمة التي طلبت الإبقاء عليها (لأغراض التوافق) ==
    // ===================================================================

    /**
     * [دالة قديمة] تحسب الرصيد بناءً على الرصيد الافتتاحي العام وكل الحركات القديمة.
     *
     * @return float
     */
    public function getLegacyCurrentBalance(): float
    {
        $initialBudgetSetting = Setting::where('key', 'total_budget')->first();
        $balance = $initialBudgetSetting ? (float) $initialBudgetSetting->value : 0;

        // إضافة الإيرادات
        $balance += Payment::where('type', 'in')->sum('amount_ils');
        $balance += KhaledVoucher::where('type', 'receipt')->sum('amount');
        $balance += MohammedVoucher::where('type', 'receipt')->sum('amount');
        $balance += WaliVoucher::where('type', 'receipt')->sum('amount');

        // طرح المصروفات
        $balance -= Payment::where('type', 'out')->sum('amount_ils');
        $balance -= KhaledVoucher::where('type', 'payment')->sum('amount');
        $balance -= MohammedVoucher::where('type', 'payment')->sum('amount');
        $balance -= WaliVoucher::where('type', 'payment')->sum('amount');

        // **تمت إضافة المصروفات التي كانت منسية**
        $balance -= Expense::sum('amount_ils');
        $balance -= SupplierExpense::sum('total_amount');

        return $balance;
    }
}
