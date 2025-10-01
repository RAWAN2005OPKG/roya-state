<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

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

Route::view('/', 'dashboard.login')->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('dashboard')->name('dashboard.')->group(function () {
 Route::get('prbancascheq', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('prbancascheq');


Route::get('projects', [App\Http\Controllers\Dashboard\ProjectController::class, 'index'])->name("project.index");
Route::get('projects/create', [App\Http\Controllers\Dashboard\ProjectController::class, 'create'])->name("project.create");
Route::get('projects/{project}', [App\Http\Controllers\Dashboard\ProjectController::class, 'show'])->name("project.show");
Route::put('projects/{project}', [App\Http\Controllers\Dashboard\ProjectController::class, 'update'])->name("project.update");
Route::post('projects', [App\Http\Controllers\Dashboard\ProjectController::class, 'store'])->name("project.store");
Route::delete('projects', [App\Http\Controllers\Dashboard\ProjectController::class, 'destroy'])->name("project.destroy");



    Route::resource('employees', App\Http\Controllers\Dashboard\EmployeeController::class);
    Route::resource('receipt-vouchers', App\Http\Controllers\Dashboard\ReceiptVoucherController::class);
    Route::resource('payment-vouchers', App\Http\Controllers\Dashboard\PaymentVoucherController::class);
    Route::resource('cash', App\Http\Controllers\Dashboard\CashTransactionController::class);
    Route::resource('bank', App\Http\Controllers\Dashboard\BankTransactionController::class);
    Route::resource('cheques', App\Http\Controllers\Dashboard\ChequeController::class);
    Route::resource('fund-transfers', App\Http\Controllers\Dashboard\FundTransferController::class);
    Route::resource('project-transfers', App\Http\Controllers\Dashboard\ProjectTransferController::class);
    Route::resource('expenses', App\Http\Controllers\Dashboard\ExpenseController::class)->only(['index', 'store']);
    Route::resource('investors', App\Http\Controllers\Dashboard\InvestorController::class)->only(['index', 'store']);
    Route::resource('investments',App\Http\Controllers\Dashboard\InvestmentController::class)->only(['index', 'store']);
    Route::resource('alerts', App\Http\Controllers\Dashboard\AlertController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('alerts/refresh', [App\Http\Controllers\Dashboard\AlertController::class, 'refreshAlerts'])->name('alerts.refresh');
    Route::get('alerts/create', [App\Http\Controllers\Dashboard\AlertController::class, 'create'])->name('alerts.create'); // إضافي لو عايزة زر "إضافة تنبيه"
      Route::get('/annual-report', [App\Http\Controllers\Dashboard\AnnualReportController::class, 'index'])->name('report.annual');
Route::resource('client-payments', App\Http\Controllers\Dashboard\ClientPaymentController::class)->only(['index', 'store', 'update', 'destroy']);
Route::get('daily-report', [App\Http\Controllers\Dashboard\DailyReportController::class, 'index'])->name('daily.index');
    Route::post('daily-report', [App\Http\Controllers\Dashboard\DailyReportController::class, 'store'])->name('daily.store');
Route::get('annual-report', [App\Http\Controllers\Dashboard\AnnualReportController::class, 'index'])->name('report.annual');
Route::resource('contracts', App\Http\Controllers\Dashboard\ContractController::class)->only(['index', 'create', 'store']);


});


Route::get('/create-test-user', function () {
App\Models\User::create([
    'email'=>"admin@app.com",
    'name'=>"admin",
            'password' => Illuminate\Support\Facades\Hash::make(123456),
]);

});

