<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// استدعاء المتحكمات المستخدمة
use App\Http\Controllers\HomeController;


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

// مجموعة مسارات لوحة التحكم
Route::prefix('dashboard')->name('dashboard.')->group(function () {

    Route::get('prbancascheq', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('home');

    // استخدام Route::resource للمسارات القياسية (CRUD)
    Route::resource('projects', App\Http\Controllers\Dashboard\ProjectController::class);
    Route::resource('employees', App\Http\Controllers\Dashboard\EmployeeController::class);
    Route::resource('receipt-vouchers', App\Http\Controllers\Dashboard\ReceiptVoucherController::class);
    Route::resource('customers', App\Http\Controllers\Dashboard\CustomerController::class);
    Route::resource('contracts', App\Http\Controllers\Dashboard\ContractController::class);
    Route::resource('fund-transfers', App\Http\Controllers\Dashboard\FundTransferController::class);
    Route::resource('project-transfers', App\Http\Controllers\Dashboard\ProjectTransferController::class);
    Route::resource('alerts', App\Http\Controllers\Dashboard\AlertController::class);
    Route::resource('expenses', App\Http\Controllers\Dashboard\ExpenseController::class)->only(['index', 'store']);
    Route::resource('investors', App\Http\Controllers\Dashboard\InvestorController::class)->only(['index', 'store']);
    Route::resource('investments', App\Http\Controllers\Dashboard\InvestmentController::class)->only(['index', 'store']);

    // المسارات المخصصة
    Route::get('/treasury', [App\Http\Controllers\Dashboard\GeneralLedgerController::class, 'index'])->name('treasury');
    Route::get('/general-ledger', [App\Http\Controllers\Dashboard\GeneralLedgerController::class, 'index'])->name('general-ledger.index');

    Route::get('/cash', [App\Http\Controllers\Dashboard\CashTransactionController::class, 'index'])->name('cash.index');
    Route::post('/cash', [App\Http\Controllers\Dashboard\CashTransactionController::class, 'store'])->name('cash.store');

    Route::get('/bank', [App\Http\Controllers\Dashboard\BankTransactionController::class, 'index'])->name('bank.index');
    Route::post('/bank', [App\Http\Controllers\Dashboard\BankTransactionController::class, 'store'])->name('bank.store');

    Route::get('/cheques', [App\Http\Controllers\Dashboard\ChequeController::class, 'index'])->name('cheques.index');
    Route::post('/cheques', [App\Http\Controllers\Dashboard\ChequeController::class, 'store'])->name('cheques.store');

    Route::post('/funds-transfers', [App\Http\Controllers\Dashboard\FundsTransferController::class, 'store'])->name('funds-transfers.store');

    Route::get('/payments', [App\Http\Controllers\Dashboard\PaymentVoucherController::class, 'index'])->name('payments.index');
    Route::post('/payments', [App\Http\Controllers\Dashboard\PaymentController::class, 'store'])->name('payments.store');

    Route::get('/add-transaction', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('add_transaction.create');

    Route::get('client-payments', [App\Http\Controllers\Dashboard\ContractController::class, 'index'])->name('client-payments');

    Route::get('daily', [App\Http\Controllers\Dashboard\DailyReportController::class, 'index'])->name('daily.index');
    Route::post('daily', [App\Http\Controllers\Dashboard\DailyReportController::class, 'store'])->name('daily.store');

    Route::get('years', [App\Http\Controllers\Dashboard\AnnualReportController::class, 'index'])->name('report.annual');

    Route::post('alerts/refresh', [App\Http\Controllers\Dashboard\AlertController::class, 'refreshAlerts'])->name('alerts.refresh');
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
