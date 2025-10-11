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


Auth::routes();


Route::view('/', 'dashboard.login')->name('login.page');
Route::get('/home', [ HomeController::class, 'index'])->name('home');


Route::prefix('dashboard')->name('dashboard.')->group(function () {

    Route::get('/expenses/export/excel', [ App\Http\Controllers\Dashboard\ExpenseController::class, 'exportExcel'])->name('expenses.export.excel');
    Route::prefix('expenses/trash')->name('expenses.trash.')->controller( App\Http\Controllers\Dashboard\ExpenseController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('expenses',  App\Http\Controllers\Dashboard\ExpenseController::class);

    // 3. المستثمرون (Investors)
    Route::get('/investors/export/excel', [ App\Http\Controllers\Dashboard\InvestorController::class, 'exportExcel'])->name('investors.export.excel');
    Route::prefix('investors/trash')->name('investors.trash.')->controller( App\Http\Controllers\Dashboard\InvestorController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('investors',  App\Http\Controllers\Dashboard\InvestorController::class)->except(['show']);

    // 4. الاستثمارات (Investments)
    Route::get('/investments/export/excel', [ App\Http\Controllers\Dashboard\InvestmentController::class, 'exportExcel'])->name('investments.export.excel');
    Route::prefix('investments/trash')->name('investments.trash.')->controller( App\Http\Controllers\Dashboard\InvestmentController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('investments',  App\Http\Controllers\Dashboard\InvestmentController::class)->except(['show']);

    Route::get('/reportproject/export/excel', [App\Http\Controllers\Dashboard\ReportProjectController::class, 'exportExcel'])->name('dashboard.reportproject.export.excel');

   Route::name('reportproject.')->group(function () {
        Route::get('reportproject/trash', [App\Http\Controllers\Dashboard\ReportProjectController::class, 'trash'])->name('trash.index');
        Route::put('reportproject/trash/{id}/restore', [App\Http\Controllers\Dashboard\ReportProjectController::class, 'restore'])->name('trash.restore');
        Route::delete('reportproject/trash/{id}/force-delete', [App\Http\Controllers\Dashboard\ReportProjectController::class, 'forceDelete'])->name('trash.forceDelete');

        Route::get('reportproject/export/excel', [App\Http\Controllers\Dashboard\ReportProjectController::class, 'exportExcel'])->name('export.excel');
    });

    Route::resource('reportproject', App\Http\Controllers\Dashboard\ReportProjectController::class);

    // 5. العقود (Contracts)
    Route::get('/contracts/export/excel', [ App\Http\Controllers\Dashboard\ContractController::class, 'exportExcel'])->name('contracts.export.excel');
    Route::get('/contracts/export/pdf/{id}', [ App\Http\Controllers\Dashboard\ContractController::class, 'exportPdf'])->name('contracts.export.pdf');
    Route::prefix('contracts/trash')->name('contracts.trash.')->controller( App\Http\Controllers\Dashboard\ContractController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('contracts',  App\Http\Controllers\Dashboard\ContractController::class);

    // 6. العملاء (Customers)
    Route::get('/customers/export/excel', [ App\Http\Controllers\Dashboard\CustomerController::class, 'exportExcel'])->name('customers.export.excel');
    Route::prefix('customers/trash')->name('customers.trash.')->controller( App\Http\Controllers\Dashboard\CustomerController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('customers',  App\Http\Controllers\Dashboard\CustomerController::class)->except(['show']);

    // 7. الموظفون (Employees)
    Route::get('/employees/export/excel', [ App\Http\Controllers\Dashboard\EmployeeController::class, 'exportExcel'])->name('employees.export.excel');
    Route::get('/employees/{employee}/pay', [ App\Http\Controllers\Dashboard\EmployeeController::class, 'showPayForm'])->name('employees.pay.form');
    Route::post('/employees/pay', [ App\Http\Controllers\Dashboard\EmployeeController::class, 'storePayment'])->name('employees.pay.store');
    Route::prefix('employees/trash')->name('employees.trash.')->controller(App\Http\Controllers\Dashboard\EmployeeController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('employees', App\Http\Controllers\Dashboard\EmployeeController::class);

    // 8. المشاريع (Projects)
    Route::get('/projects/export/excel', [App\Http\Controllers\Dashboard\ProjectController::class, 'exportExcel'])->name('projects.export.excel');
    Route::prefix('projects/trash')->name('projects.trash.')->controller(App\Http\Controllers\Dashboard\ProjectController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('projects',App\Http\Controllers\Dashboard\ProjectController::class);

    Route::get('/daily-report', [App\Http\Controllers\Dashboard\DailyReportController::class, 'index'])->name('daily.index');
    Route::post('/daily-report', [App\Http\Controllers\Dashboard\DailyReportController::class, 'store'])->name('daily.store');

    Route::resource('receipt-vouchers', App\Http\Controllers\Dashboard\ReceiptVoucherController::class)->except(['show']);
    Route::resource('fund-transfers', App\Http\Controllers\Dashboard\FundTransferController::class)->except(['show']);
    Route::resource('project-transfers', App\Http\Controllers\Dashboard\ProjectTransferController::class)->except(['show']);
    Route::resource('alerts', App\Http\Controllers\Dashboard\AlertController::class);

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
    Route::get('years', [App\Http\Controllers\Dashboard\AnnualReportController::class, 'index'])->name('report.annual');
    Route::post('alerts/refresh', [App\Http\Controllers\Dashboard\AlertController::class, 'refreshAlerts'])->name('alerts.refresh');
});


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
