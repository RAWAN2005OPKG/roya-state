<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Auth\LoginController;

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

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/', [LoginController::class, 'login']);

Route::post('logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {

    // الصفحة الرئيسية للوحة التحكم
    Route::get('/', [App\Http\Controllers\Dashboard\HomeController::class, 'index'])->name('home');
    Route::get('/home', [App\Http\Controllers\Dashboard\HomeController::class, 'index'])->name('home');

// --- الوحدات المالية ---
    Route::resource('accounts', App\Http\Controllers\Dashboard\AccountController::class);
    Route::resource('journal-entries', App\Http\Controllers\Dashboard\JournalEntryController::class);
    Route::resource('expenses', App\Http\Controllers\Dashboard\ExpenseController::class);
    Route::resource('cash-safes', App\Http\Controllers\Dashboard\CashSafeController::class)->except(['create', 'show', 'edit']);
    Route::resource('bank-accounts', App\Http\Controllers\Dashboard\BankAccountController::class)->except(['create', 'show', 'edit']);
    Route::get('/financial-summary', [App\Http\Controllers\Dashboard\FinancialController::class, 'summary'])->name('financial.summary');
// Cash Safes Routes
Route::prefix('cash-safes')->name('cash-safes.')->group(function () {
    Route::get('/', [App\Http\Controllers\Dashboard\CashSafeController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Dashboard\CashSafeController::class, 'store'])->name('store');
    Route::put('/{cashSafe}', [App\Http\Controllers\Dashboard\CashSafeController::class, 'update'])->name('update');
    Route::delete('/{cashSafe}', [App\Http\Controllers\Dashboard\CashSafeController::class, 'destroy'])->name('destroy');

    // Trash Routes
    Route::prefix('trash')->name('trash.')->group(function () {
        Route::get('/', [App\Http\Controllers\Dashboard\CashSafeController::class, 'trash'])->name('index');
        Route::patch('/{id}/restore', [App\Http\Controllers\Dashboard\CashSafeController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [App\Http\Controllers\Dashboard\CashSafeController::class, 'forceDelete'])->name('force-delete');
    });
});
// Bank Accounts Routes
Route::prefix('bank-accounts')->name('bank-accounts.')->group(function () {
    Route::get('/', [App\Http\Controllers\Dashboard\BankAccountController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Dashboard\BankAccountController::class, 'store'])->name('store');
    Route::put('/{bankAccount}', [App\Http\Controllers\Dashboard\BankAccountController::class, 'update'])->name('update');
    Route::delete('/{bankAccount}', [App\Http\Controllers\Dashboard\BankAccountController::class, 'destroy'])->name('destroy');

    // Trash Routes
    Route::prefix('trash')->name('trash.')->group(function () {
        Route::get('/', [App\Http\Controllers\Dashboard\BankAccountController::class, 'trash'])->name('index');
        Route::patch('/{id}/restore', [App\Http\Controllers\Dashboard\BankAccountController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [App\Http\Controllers\Dashboard\BankAccountController::class, 'forceDelete'])->name('force-delete');
    });
});

    // --- وحدات التحويلات ---
    Route::resource('fund-transfers', App\Http\Controllers\Dashboard\FundTransferController::class)->only(['index', 'store']);
    Route::resource('project-transfers', App\Http\Controllers\Dashboard\ProjectTransferController::class)->only(['index', 'store']);


    Route::resource('project-transfers', App\Http\Controllers\Dashboard\ProjectTransferController::class)->only(['index', 'store']);
Route::resource('journal-entries', App\Http\Controllers\Dashboard\JournalEntryController::class);

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

    // 6. العقود (Contracts)
Route::name('contracts.')->group(function () {
    Route::get('contracts/trash', [ App\Http\Controllers\Dashboard\ContractController::class, 'trash'])->name('trash.index');
    Route::post('contracts/restore/{id}', [ App\Http\Controllers\Dashboard\ContractController::class, 'restore'])->name('trash.restore'); // استخدمت POST بدلاً من PUT لأنها أكثر شيوعاً للاستعادة
    Route::delete('contracts/forceDelete/{id}', [ App\Http\Controllers\Dashboard\ContractController::class, 'forceDelete'])->name('trash.forceDelete');

   Route::get('contracts/export/excel', [ App\Http\Controllers\Dashboard\ContractController::class, 'exportExcel'])->name('export.excel');
});

Route::resource('contracts',  App\Http\Controllers\Dashboard\ContractController::class);


// 7. الدفعات (Payments)
Route::prefix('contracts/{contract}')->as('contracts.')->group(function () {
    Route::get('payments/create', [ App\Http\Controllers\Dashboard\PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [ App\Http\Controllers\Dashboard\PaymentController::class, 'store'])->name('payments.store');
    Route::delete('payments/{payment}', [ App\Http\Controllers\Dashboard\PaymentController::class, 'destroy'])->name('payments.destroy');
});

    // 6. العملاء (Customers)
     Route::get('/customers/export/excel', [App\Http\Controllers\Dashboard\CustomerController::class, 'exportExcel'])->name('customers.export.excel');
    Route::prefix('customers/trash')->name('customers.trash.')->controller(App\Http\Controllers\Dashboard\CustomerController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::post('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('customers', App\Http\Controllers\Dashboard\CustomerController::class);
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

     // 9. المقاولون والموردون (Subcontractors)
    Route::get('/subcontractors/export/excel', [App\Http\Controllers\Dashboard\SubcontractorController::class, 'exportExcel'])->name('subcontractors.export.excel');

    Route::prefix('subcontractors/trash')->name('subcontractors.trash.')->controller(App\Http\Controllers\Dashboard\SubcontractorController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('subcontractors', App\Http\Controllers\Dashboard\SubcontractorController::class);

//  المشتريات (Purchases)
Route::prefix('purchases')->as('purchases.')->group(function () {

    // فواتير المشتريات (Purchase Invoices)
    Route::get('invoices/trash', [App\Http\Controllers\Dashboard\PurchaseInvoiceController::class, 'trash'])->name('invoices.trash');
    Route::post('invoices/restore/{id}', [App\Http\Controllers\Dashboard\PurchaseInvoiceController::class, 'restore'])->name('invoices.restore');
    Route::delete('invoices/forceDelete/{id}', [App\Http\Controllers\Dashboard\PurchaseInvoiceController::class, 'forceDelete'])->name('invoices.forceDelete');
    Route::resource('invoices', App\Http\Controllers\Dashboard\PurchaseInvoiceController::class);

});
//  الموردون (Suppliers)
Route::prefix('suppliers')->as('suppliers.')->group(function () {
    // مسارات سلة المحذوفات
    Route::get('trash', [App\Http\Controllers\Dashboard\SupplierController::class, 'trash'])->name('trash');
    Route::post('restore/{id}', [App\Http\Controllers\Dashboard\SupplierController::class, 'restore'])->name('restore');
    Route::delete('forceDelete/{id}', [App\Http\Controllers\Dashboard\SupplierController::class, 'forceDelete'])->name('forceDelete');

    // مسارات CRUD الأساسية
    Route::resource('/', App\Http\Controllers\Dashboard\SupplierController::class)->parameters(['' => 'supplier']);
});
   Route::prefix('products/trash')->name('products.trash.')->controller(App\Http\Controllers\Dashboard\ProductController::class)->group(function () {
        Route::get('/', 'trash')->name('index');
        Route::post('/{id}/restore', 'restore')->name('restore');
        Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
    });
    Route::resource('products',App\Http\Controllers\Dashboard\ProductController::class);

    Route::prefix('warehouses/trash')->name('warehouses.trash.')->controller(App\Http\Controllers\Dashboard\WarehouseController::class)->group(function () {
    Route::get('/', 'trash')->name('index');
    Route::post('/{id}/restore', 'restore')->name('restore');
    Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
});
Route::resource('warehouses', App\Http\Controllers\Dashboard\WarehouseController::class)->except(['show', 'create', 'edit']);

Route::resource('quotations', App\Http\Controllers\Dashboard\QuotationController::class);
Route::resource('sales', App\Http\Controllers\Dashboard\SaleInvoiceController::class);
Route::resource('sales-returns', App\Http\Controllers\Dashboard\SaleReturnController::class);
Route::resource('quotations', App\Http\Controllers\Dashboard\QuotationController::class);
Route::post('quotations/{quotation}/convert', [App\Http\Controllers\Dashboard\QuotationController::class, 'convertToInvoice'])->name('quotations.convert');

Route::resource('sales', App\Http\Controllers\Dashboard\SaleInvoiceController::class);
Route::get('collections', [App\Http\Controllers\Dashboard\SaleInvoiceController::class, 'collections'])->name('collections');

Route::resource('sales-returns', App\Http\Controllers\Dashboard\SaleReturnController::class);
Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {

    Route::get('collections', [App\Http\Controllers\Dashboard\SaleInvoiceController::class, 'collections'])->name('collections');

    Route::post('sales/{sale}/add-payment', [App\Http\Controllers\Dashboard\SaleInvoiceController::class, 'addPayment'])->name('sales.addPayment');

});
// الأذون المخزنية
Route::prefix('transfers/trash')->name('transfers.trash.')->controller(App\Http\Controllers\Dashboard\StockTransferController::class)->group(function () {
    Route::get('/', 'trash')->name('index');
    Route::post('/{id}/restore', 'restore')->name('restore');
    Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
});
Route::resource('transfers', App\Http\Controllers\Dashboard\StockTransferController::class);
// قوائم الأسعار
Route::prefix('pricelists/trash')->name('pricelists.trash.')->controller(App\Http\Controllers\Dashboard\PriceListController::class)->group(function () {
    Route::get('/', 'trash')->name('index');
    Route::post('/{id}/restore', 'restore')->name('restore');
    Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
});
Route::resource('pricelists', App\Http\Controllers\Dashboard\PriceListController::class);
// إدارة الجرد
Route::prefix('stocktakes/trash')->name('stocktakes.trash.')->controller(App\Http\Controllers\Dashboard\StocktakeController::class)->group(function () {
    Route::get('/', 'trash')->name('index');
    Route::post('/{id}/restore', 'restore')->name('restore');
    Route::delete('/{id}/force-delete', 'forceDelete')->name('forceDelete');
});
Route::resource('stocktakes', App\Http\Controllers\Dashboard\StocktakeController::class);

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
