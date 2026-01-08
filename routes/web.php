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
Route::prefix('/khaleed-mohamed')->name('khaleed-mohamed.')->group(function () {
    Route::get('/trash', [App\Http\Controllers\Dashboard\KhaleedMohamedController::class, 'trash'])->name('trash');
    Route::patch('/{id}/restore', [App\Http\Controllers\Dashboard\KhaleedMohamedController::class, 'restore'])->name('restore');
    Route::delete('/{id}/force-delete', [App\Http\Controllers\Dashboard\KhaleedMohamedController::class, 'forceDelete'])->name('force-delete');
});
Route::resource('/khaleed-mohamed', App\Http\Controllers\Dashboard\KhaleedMohamedController::class);
// --- الوحدات المالية ---
    Route::resource('accounts', App\Http\Controllers\Dashboard\AccountController::class);
    Route::resource('journal-entries', App\Http\Controllers\Dashboard\JournalEntryController::class);
    Route::resource('expenses', App\Http\Controllers\Dashboard\ExpenseController::class);
    Route::resource('/bank-accounts', App\Http\Controllers\Dashboard\BankAccountController::class)->names('bank-accounts');
    Route::get('/financial-summary', [App\Http\Controllers\Dashboard\FinancialController::class, 'summary'])->name('financial.summary');

    // مسار المركز المالي
    Route::get('/financial-accounts', [App\Http\Controllers\Dashboard\FinancialAccountsController::class, 'index'])->name('financial-accounts.index');
//مسار ولي خالص
Route::resource('/waleed-transactions', App\Http\Controllers\Dashboard\WaleedTransactionController::class);

Route::get('/waleed-transactions/trash', [App\Http\Controllers\Dashboard\WaleedTransactionController::class, 'trash'])->name('waleed-transactions.trash');
Route::put('/waleed-transactions/{id}/restore', [App\Http\Controllers\Dashboard\WaleedTransactionController::class, 'restore'])->name('waleed-transactions.restore');
Route::delete('/waleed-transactions/{id}/force-delete', [App\Http\Controllers\Dashboard\WaleedTransactionController::class, 'forceDelete'])->name('waleed-transactions.forceDelete');
// مسارات إدارة الخزائن
    Route::resource('/cash-safes', App\Http\Controllers\Dashboard\CashSafeController::class)->names('cash-safes');
// مسار الربح السنوي
Route::get('/annual-profit', [App\Http\Controllers\Dashboard\AnnualProfitController::class, 'index'])->name('annual-profit.index');

// مسار التنبيهات
Route::get('/alerts', [App\Http\Controllers\Dashboard\AlertController::class, 'index'])->name('alerts.index');
    // مسارات إدارة الحسابات البنكية (مع الحركات)
    Route::get('/bank-accounts/{bankAccount}', [App\Http\Controllers\Dashboard\BankAccountController::class, 'show'])->name('bank-accounts.show');
    Route::post('/bank-accounts/{bankAccount}/transactions', [App\Http\Controllers\Dashboard\BankAccountController::class, 'storeTransaction'])->name('bank-accounts.transactions.store');
    Route::get('/bank-transactions/{transaction}/edit', [App\Http\Controllers\Dashboard\BankAccountController::class, 'editTransaction'])->name('bank-accounts.transactions.edit');
    Route::put('/bank-transactions/{transaction}', [App\Http\Controllers\Dashboard\BankAccountController::class, 'updateTransaction'])->name('bank-accounts.transactions.update');
    Route::resource('/bank-accounts', App\Http\Controllers\Dashboard\BankAccountController::class)->except(['show'])->names('bank-accounts');
 Route::get('bank-accounts/{bankAccount}/statement', [App\Http\Controllers\Dashboard\BankAccountStatementController::class, 'show'])->name('bank-accounts.statement.show');

    // مسار لتخزين الحركة الجديدة من صفحة كشف الحساب
    Route::post('bank-accounts/{bankAccount}/transactions', [App\Http\Controllers\Dashboard\BankAccountStatementController::class, 'store'])->name('bank-accounts.transactions.store');

    // مسارات إدارة الشيكات
    Route::resource('/checks', App\Http\Controllers\Dashboard\CheckController::class)->names('checks');

    // مسارات دليل البنوك
    Route::resource('/banks', App\Http\Controllers\Dashboard\BankController::class);
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
// Fund Transfers Routes
Route::get('/fund-transfers', [App\Http\Controllers\Dashboard\FundTransferController::class, 'index'])->name('fund-transfers.index');
Route::post('/fund-transfers', [App\Http\Controllers\Dashboard\FundTransferController::class, 'store'])->name('fund-transfers.store');
// Financial Accounts (Banks, Safes, Checks) Main Page
Route::get('/financial-accounts', [App\Http\Controllers\Dashboard\FinancialAccountsController::class, 'index'])->name('financial-accounts.index');
// Checks Management Routes
Route::prefix('checks')->name('checks.')->group(function () {
    Route::get('/create', [App\Http\Controllers\Dashboard\CheckController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Dashboard\CheckController::class, 'store'])->name('store');
    Route::get('/{check}/edit', [App\Http\Controllers\Dashboard\CheckController::class, 'edit'])->name('edit');
    Route::put('/{check}', [App\Http\Controllers\Dashboard\CheckController::class, 'update'])->name('update');
    Route::delete('/{check}', [App\Http\Controllers\Dashboard\CheckController::class, 'destroy'])->name('destroy');
    Route::post('/{check}/update-status', [App\Http\Controllers\Dashboard\CheckController::class, 'updateStatus'])->name('update-status');
});
Route::prefix('bank-transactions')->name('bank-transactions.')->group(function () {
        Route::get('/trash', [App\Http\Controllers\Dashboard\BankTransactionController::class, 'trash'])->name('trash');
        Route::patch('/{id}/restore', [App\Http\Controllers\Dashboard\BankTransactionController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [App\Http\Controllers\Dashboard\BankTransactionController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('bank-transactions', App\Http\Controllers\Dashboard\BankTransactionController::class);
    // --- وحدات التحويلات ---
    Route::resource('fund-transfers', App\Http\Controllers\Dashboard\FundTransferController::class)->only(['index', 'store']);
    Route::resource('project-transfers', App\Http\Controllers\Dashboard\ProjectTransferController::class)->only(['index', 'store']);
 Route::get('/purchases', [App\Http\Controllers\Dashboard\PurchaseController::class, 'index'])->name('purchases.index');

    Route::resource('purchases', App\Http\Controllers\Dashboard\PurchaseController::class);
 Route::get('purchase-returns', [App\Http\Controllers\Dashboard\PurchaseReturnController::class, 'index'])->name('purchase-returns.index');

    Route::get('purchase-returns/create', [App\Http\Controllers\Dashboard\PurchaseReturnController::class, 'create'])->name('purchase-returns.create');
    Route::post('purchase-returns', [App\Http\Controllers\Dashboard\PurchaseReturnController::class, 'store'])->name('purchase-returns.store');
    Route::delete('purchase-returns/{purchase_return}', [App\Http\Controllers\Dashboard\PurchaseReturnController::class, 'destroy'])->name('purchase-returns.destroy');

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

    Route::get('/reportproject/export/excel', [App\Http\Controllers\Dashboard\ReportProjectController::class, 'exportExcel'])->name('dashboard.reportproject.export.excel');

   Route::name('reportproject.')->group(function () {
        Route::get('reportproject/trash', [App\Http\Controllers\Dashboard\ReportProjectController::class, 'trash'])->name('trash.index');
        Route::put('reportproject/trash/{id}/restore', [App\Http\Controllers\Dashboard\ReportProjectController::class, 'restore'])->name('trash.restore');
        Route::delete('reportproject/trash/{id}/force-delete', [App\Http\Controllers\Dashboard\ReportProjectController::class, 'forceDelete'])->name('trash.forceDelete');

        Route::get('reportproject/export/excel', [App\Http\Controllers\Dashboard\ReportProjectController::class, 'exportExcel'])->name('export.excel');
    });

    Route::resource('reportproject', App\Http\Controllers\Dashboard\ReportProjectController::class);

    // 6. العقود (Contracts)

Route::resource('contracts',  App\Http\Controllers\Dashboard\ContractController::class);

    Route::get('contracts/trash', [App\Http\Controllers\Dashboard\ContractController::class, 'trash'])->name('contracts.trash');
    Route::post('contracts/{id}/restore', [App\Http\Controllers\Dashboard\ContractController::class, 'restore'])->name('contracts.restore');
    Route::delete('contracts/{id}/force-delete', [App\Http\Controllers\Dashboard\ContractController::class, 'forceDelete'])->name('contracts.forceDelete');
  // --- مسار AJAX الجديد لجلب الكيانات في صفحة العقود ---
 Route::resource('contracts', App\Http\Controllers\Dashboard\ContractController::class);
    Route::get('get-contractables', [App\Http\Controllers\Dashboard\ContractController::class, 'getContractables'])->name('getContractables');
 Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [App\Http\Controllers\Dashboard\PaymentController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Dashboard\PaymentController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Dashboard\PaymentController::class, 'store'])->name('store');

});    Route::resource('payments', App\Http\Controllers\Dashboard\PaymentController::class)->only(['index', 'create', 'store']);
    Route::get('get-payable-details', [App\Http\Controllers\Dashboard\PaymentController::class, 'getPayableDetails'])->name('getPayableDetails');
      Route::get('get-payables', [App\Http\Controllers\Dashboard\PaymentController::class, 'getPayables'])->name('getPayables');

    Route::resource('clients', App\Http\Controllers\Dashboard\ClientController::class);

Route::prefix('clients')->name('clients.')->group(function () {
    Route::get('/', [App\Http\Controllers\Dashboard\ClientController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Dashboard\ClientController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Dashboard\ClientController::class, 'store'])->name('store');
});
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
Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [App\Http\Controllers\Dashboard\ProjectController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Dashboard\ProjectController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Dashboard\ProjectController::class, 'store'])->name('store');
        Route::get('/{project}', [App\Http\Controllers\Dashboard\ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [App\Http\Controllers\Dashboard\ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project}', [App\Http\Controllers\Dashboard\ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [App\Http\Controllers\Dashboard\ProjectController::class, 'destroy'])->name('destroy');
    });
    Route::get('/daily-report', [App\Http\Controllers\Dashboard\DailyReportController::class, 'index'])->name('daily.index');
    Route::post('/daily-report', [App\Http\Controllers\Dashboard\DailyReportController::class, 'store'])->name('daily.store');

    Route::resource('receipt-vouchers', App\Http\Controllers\Dashboard\ReceiptVoucherController::class)->except(['show']);
    Route::resource('fund-transfers', App\Http\Controllers\Dashboard\FundTransferController::class)->except(['show']);
    Route::resource('project-transfers', App\Http\Controllers\Dashboard\ProjectTransferController::class)->except(['show']);
    Route::resource('alerts', App\Http\Controllers\Dashboard\AlertController::class);

     // 9. المقاولون والموردون (Subcontractors)
  Route::resource('subcontractors', App\Http\Controllers\Dashboard\SubcontractorController::class);

Route::get('subcontractors/trash', [App\Http\Controllers\Dashboard\SubcontractorController::class, 'trash'])->name('subcontractors.trash');
Route::post('subcontractors/{id}/restore', [App\Http\Controllers\Dashboard\SubcontractorController::class, 'restore'])->name('subcontractors.restore');
Route::delete('subcontractors/{id}/force-delete', [App\Http\Controllers\Dashboard\SubcontractorController::class, 'forceDelete'])->name('subcontractors.forceDelete');
Route::get('subcontractors/export/excel', [App\Http\Controllers\Dashboard\SubcontractorController::class, 'exportExcel'])->name('subcontractors.exportExcel');
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
});


Route::get('/create-test-user', function () {
    if (!App\Models\User::where('email', 'rayapalinfo@gmail.com')->exists()) {
        App\Models\User::create([
            'email' => "rayapalinfo@gmail.com",
            'name' => "khalid",
            'password' => Illuminate\Support\Facades\Hash::make('khalid@20252'),
        ]);
        return "Test user created.";
    }
    return "Test user already exists.";
});
