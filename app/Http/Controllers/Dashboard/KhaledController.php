<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Khaled;
use App\Models\Project;
use App\Models\Client;
use App\Models\Investor;
use App\Models\BankAccount;
use App\Models\CashSafe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KhaledsExport;

class KhaledController extends Controller
{
    // دالة لجلب سعر الصرف الحالي
    private function getExchangeRate($currency)
    {
        if ($currency === 'ILS') {
            return 1;
        }
        try {
            // يمكنك استبدال هذه الـ API بأي API أخرى تفضلها
            $response = Http::get("https://api.exchangerate-api.com/v4/latest/{$currency}" );
            if ($response->successful() && isset($response->json()['rates']['ILS'])) {
                return $response->json()['rates']['ILS'];
            }
        } catch (\Exception $e) {
            // في حال فشل الـ API، استخدم سعر صرف افتراضي
        }
        // أسعار صرف افتراضية في حال فشل الـ API
        $defaultRates = ['USD' => 3.75, 'JOD' => 5.25];
        return $defaultRates[$currency] ?? 1;
    }

    public function index(Request $request)
    {
        $query = Khaled::with(['project', 'client', 'investor'])->latest();

        // فلترة البحث
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('id', $request->search);
        }

        $limit = $request->input('limit', 10);
        $khaleds = $query->paginate($limit);

        return view('dashboard.khaled.index', compact('khaleds'));
    }

    public function create()
    {
        $projects = Project::all();
        $clients = Client::all();
        $investors = Investor::all();
        $bankAccounts = BankAccount::with('bank')->get();
        $cashSafes = CashSafe::all();

        // جلب أسعار الصرف الأولية
        $exchangeRates = [
            'USD' => $this->getExchangeRate('USD'),
            'JOD' => $this->getExchangeRate('JOD'),
        ];

        return view('dashboard.khaled.create', compact('projects', 'clients', 'investors', 'bankAccounts', 'cashSafes', 'exchangeRates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'voucher_date' => 'required|date',
            'type' => 'required|in:receipt,payment',
            'payment_method' => 'required|in:cash,bank_transfer,check',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'exchange_rate' => 'required|numeric',
            'project_id' => 'nullable|exists:projects,id',
            'client_id' => 'nullable|exists:clients,id',
            'investor_id' => 'nullable|exists:investors,id',
            'notes' => 'nullable|string',
            'from_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'to_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'cash_safe_id' => 'nullable|exists:cash_safes,id',
            'handler_name' => 'nullable|string',
            'handler_role' => 'nullable|string',
            'check_number' => 'nullable|string',
            'check_owner_name' => 'nullable|string',
            'check_bank_name' => 'nullable|string',
            'check_due_date' => 'nullable|date',
        ]);

        // حساب القيمة بالشيكل
        $data['amount_ils'] = $data['amount'] * $data['exchange_rate'];

        Khaled::create($data);

        return redirect()->route('dashboard.khaled.index')->with('success', 'تم إنشاء السند بنجاح.');
    }

    public function show(Khaled $khaled)
    {
        $khaled->load(['project', 'client', 'investor', 'fromBankAccount.bank', 'toBankAccount.bank', 'cashSafe']);
        return view('dashboard.khaled.show', compact('khaled'));
    }

    public function edit(Khaled $khaled)
    {
        $projects = Project::all();
        $clients = Client::all();
        $investors = Investor::all();
        $bankAccounts = BankAccount::with('bank')->get();
        $cashSafes = CashSafe::all();

        $exchangeRates = [
            'USD' => $this->getExchangeRate('USD'),
            'JOD' => $this->getExchangeRate('JOD'),
        ];

        return view('dashboard.khaled.edit', compact('khaled', 'projects', 'clients', 'investors', 'bankAccounts', 'cashSafes', 'exchangeRates'));
    }

    public function update(Request $request, Khaled $khaled)
    {
        $data = $request->validate([
            'voucher_date' => 'required|date',
            'type' => 'required|in:receipt,payment',
            'payment_method' => 'required|in:cash,bank_transfer,check',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'exchange_rate' => 'required|numeric',
            'project_id' => 'nullable|exists:projects,id',
            'client_id' => 'nullable|exists:clients,id',
            'investor_id' => 'nullable|exists:investors,id',
            'notes' => 'nullable|string',
            // ... add other fields for validation
        ]);

        $data['amount_ils'] = $data['amount'] * $data['exchange_rate'];
        $khaled->update($data);

        return redirect()->route('dashboard.khaled.index')->with('success', 'تم تحديث السند بنجاح.');
    }

    public function destroy(Khaled $khaled)
    {
        $khaled->delete(); // Soft delete
        return redirect()->route('dashboard.khaled.index')->with('success', 'تم نقل السند إلى سلة المحذوفات.');
    }

    // --- سلة المحذوفات ---
    public function trash(Request $request)
    {
        $query = Khaled::onlyTrashed()->latest();
        $limit = $request->input('limit', 10);
        $khaleds = $query->paginate($limit);
        return view('dashboard.khaled.trash', compact('khaleds'));
    }

    public function restore($id)
    {
        Khaled::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('dashboard.khaled.trash')->with('success', 'تم استعادة السند بنجاح.');
    }

    public function forceDelete($id)
    {
        Khaled::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('dashboard.khaled.trash')->with('success', 'تم حذف السند نهائياً.');
    }

    // --- تصدير Excel ---
    public function exportExcel()
    {
        return Excel::download(new KhaledsExport, 'khaleds_data.xlsx');
    }

    // --- API لجلب سعر الصرف ---
    public function getRateAjax(Request $request)
    {
        $currency = $request->query('currency', 'USD');
        $rate = $this->getExchangeRate($currency);
        return response()->json(['rate' => $rate]);
    }
}
