<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// استدعاء المتحكمات المستخدمة
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Dashboard; // استيراد مساحة الاسم لتبسيط الكود

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| هنا تسجل جميع الراوتات الخاصة بالويب لتطبيقك.
| هذه الراوتات يتم تحميلها من RouteServiceProvider داخل مجموعة "web" ميدل وير.
|
*/

// مسارات المصادقة (تسجيل الدخول، تسجيل، الخ)
Auth::routes();

// الصفحة الرئيسية وصفحة تسجيل الدخول
Route::view('/', 'dashboard.login')->name('login.page');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// =====================================================================
// مجموعة مسارات لوحة التحكم الرئيسية
// =====================================================================
Route::prefix('dashboard')->name('dashboard.')->group(function () {

    Route::get('prbancascheq', [Dashboard\DashboardController::class, 'index'])->name('home');

    // --- مسارات المصروفات (Expenses) ---
    Route::get('/expenses/export/excel', [Dashboard\ExpenseController::class, 'exportExcel'])->name('expenses.export.excel');
    Route::prefix('expenses/trash')->name('expenses.trash.')->controller(Dashboard\ExpenseController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('expenses', Dashboard\ExpenseController::class)->except(['show']);

    // --- مسارات المستثمرين (Investors) ---
    Route::get('/investors/export/excel', [Dashboard\InvestorController::class, 'exportExcel'])->name('investors.export.excel');
    Route::prefix('investors/trash')->name('investors.trash.')->controller(Dashboard\InvestorController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('investors', Dashboard\InvestorController::class)->except(['show']);

    // --- مسارات الاستثمارات (Investments) ---
    Route::get('/investments/export/excel', [Dashboard\InvestmentController::class, 'exportExcel'])->name('investments.export.excel');
    Route::prefix('investments/trash')->name('investments.trash.')->controller(Dashboard\InvestmentController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('investments', Dashboard\InvestmentController::class)->except(['show']);

    // --- باقي مسارات الـ Resource ---
    Route::resource('projects', Dashboard\ProjectController::class)->except(['show']);
    Route::resource('employees', Dashboard\EmployeeController::class)->except(['show']);
    Route::resource('receipt-vouchers', Dashboard\ReceiptVoucherController::class)->except(['show']);
    Route::resource('customers', Dashboard\CustomerController::class)->except(['show']);
    Route::resource('contracts', Dashboard\ContractController::class)->except(['show']);
    Route::resource('fund-transfers', Dashboard\FundTransferController::class)->except(['show']);
    Route::resource('project-transfers', Dashboard\ProjectTransferController::class)->except(['show']);
    Route::resource('alerts', Dashboard\AlertController::class); // يمكن ترك show هنا إذا كنت تستخدمها

    // --- المسارات المخصصة ---
    Route::get('/treasury', [Dashboard\GeneralLedgerController::class, 'index'])->name('treasury');
    Route::get('/general-ledger', [Dashboard\GeneralLedgerController::class, 'index'])->name('general-ledger.index');
    Route::get('/cash', [Dashboard\CashTransactionController::class, 'index'])->name('cash.index');
    Route::post('/cash', [Dashboard\CashTransactionController::class, 'store'])->name('cash.store');
    Route::get('/bank', [Dashboard\BankTransactionController::class, 'index'])->name('bank.index');
    Route::post('/bank', [Dashboard\BankTransactionController::class, 'store'])->name('bank.store');
    Route::get('/cheques', [Dashboard\ChequeController::class, 'index'])->name('cheques.index');
    Route::post('/cheques', [Dashboard\ChequeController::class, 'store'])->name('cheques.store');
    Route::post('/funds-transfers', [Dashboard\FundsTransferController::class, 'store'])->name('funds-transfers.store');
    Route::get('/payments', [Dashboard\PaymentVoucherController::class, 'index'])->name('payments.index');
    Route::post('/payments', [Dashboard\PaymentController::class, 'store'])->name('payments.store');
    Route::get('/add-transaction', [Dashboard\DashboardController::class, 'index'])->name('add_transaction.create');
    Route::get('client-payments', [Dashboard\ContractController::class, 'index'])->name('client-payments');
    Route::get('daily', [Dashboard\DailyReportController::class, 'index'])->name('daily.index');
    Route::post('daily', [Dashboard\DailyReportController::class, 'store'])->name('daily.store');
    Route::get('years', [Dashboard\AnnualReportController::class, 'index'])->name('report.annual');
    Route::post('alerts/refresh', [Dashboard\AlertController::class, 'refreshAlerts'])->name('alerts.refresh');
});


// مسار لإنشاء مستخدم تجريبي (يفضل حذفه في بيئة الإنتاج)
Route::get('/create-test-user', function () {
    if (!App\Models\User::where('email', 'admin@app.com')->exists()) {
        App\Models\User::create([
            'email' => "admin@app.com",
            'name' => "admin",
            'password' => Illuminate\Support\Facades\Hash::make('123456'),
        ]);
        return "Test user created.";
    }
    return "Test user already exists.";
});
