<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ClientPaymentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SalaryPaymentController;
use App\Http\Controllers\FundTransferController;
use App\Http\Controllers\ProjectTransferController;
use App\Http\Controllers\AnnualReportController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\CashTransactionController;
use App\Http\Controllers\BankTransactionController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\ReceiptVoucherController;
use App\Http\Controllers\PaymentVoucherController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\ContractController;

// عند زيارة الرابط الرئيسي، يتم توجيه المستخدم لصفحة الدخول
Route::get('/', function () {
    return redirect()->route('login');
});

// مسار لعرض صفحة تسجيل الدخول
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

// مسار لمعالجة بيانات فورم تسجيل الدخول (تم التصحيح)
Route::post('login', [AuthController::class, 'login']);

// تسجيل الخروج (يتطلب تسجيل الدخول)
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

//  مجموعة مسارات لوحة التحكم (Dashboard) - **محمية ومخصصة للمستخدمين المسجلين فقط**
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {

    Route::redirect('', '/dashboard/home')->name('index');
    Route::view('/home', 'dashboard.home')->name('home');
    // تقرير الربح السنوي
    Route::get('/years', [AnnualReportController::class, 'index'])->name('years');
    Route::get('/report/annual', [AnnualReportController::class, 'index'])->name('report.annual');
 
    Route::view('/salary_payments', 'dashboard.salary_payments')->name('salary_payments');
    Route::view('/main', 'dashboard.main')->name('main');
    Route::view('/investors', 'dashboard.investors')->name('investors');
    Route::view('/expenses', 'dashboard.expenses')->name('expenses');
    Route::view('/employees', 'dashboard.employees')->name('employees');
    Route::view('/daily', 'dashboard.daily')->name('daily');
    Route::view('/customers', 'dashboard.customers')->name('customers');
    Route::view('/Control', 'dashboard.Control')->name('control');
    Route::view('/client_payments', 'dashboard.client_payments')->name('client_payments');
    // إضافة مشروع جديد: عرض الفورم عبر الكنترولر لضمان التوافق مع التخزين
    Route::get('/add-project', [ProjectController::class, 'create'])->name('add-project');
    Route::view('/cheques', 'dashboard.cheques')->name('cheques');
    Route::view('/new-contract', 'dashboard.new-contract')->name('new-contract');
    Route::view('/bank', 'dashboard.bank')->name('bank');
    Route::view('/cash', 'dashboard.cash')->name('cash');
    Route::view('/alernt', 'dashboard.alernt')->name('alernt');
    Route::view('/payments', 'dashboard.payments')->name('payments');
 
    // إدارة المشاريع
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');              # قائمة المشاريع
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create'); # عرض نموذج الإضافة
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');        # حفظ مشروع جديد
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy'); # حذف مشروع
 
    // تحويلات الخزينة
    Route::post('/treasury/fund-transfers', [FundTransferController::class, 'store'])->name('treasury.fund-transfers.store');
    Route::post('/treasury/project-transfers', [ProjectTransferController::class, 'store'])->name('treasury.project-transfers.store');
 
    // التقارير اليومية - حفظ التقرير اليدوي
    Route::post('/daily/manual-report', [DailyReportController::class, 'store'])->name('daily.manual-report.store');
 
    // الموظفون والرواتب
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::post('/salary-payments', [SalaryPaymentController::class, 'store'])->name('salary-payments.store');
 
    // عملاء
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::post('/customer-payments', [ClientPaymentController::class, 'store'])->name('customer-payments.store');
 
    // الكاش والبنك والشيكات
    Route::post('/cash-transactions', [CashTransactionController::class, 'store'])->name('cash-transactions.store');
    Route::post('/bank-transactions', [BankTransactionController::class, 'store'])->name('bank-transactions.store');
    Route::post('/cheques', [ChequeController::class, 'store'])->name('cheques.store');
 
    // سندات القبض والصرف
    Route::post('/receipt-vouchers', [ReceiptVoucherController::class, 'store'])->name('receipt-vouchers.store');
    Route::post('/payment-vouchers', [PaymentVoucherController::class, 'store'])->name('payment-vouchers.store');
 
    // المستثمرون والاستثمارات
    Route::post('/investors', [InvestorController::class, 'store'])->name('investors.store');
    Route::post('/investments', [InvestmentController::class, 'store'])->name('investments.store');
 
    // العقود
    Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
 
    // حفظ مصروف جديد
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
 
});
