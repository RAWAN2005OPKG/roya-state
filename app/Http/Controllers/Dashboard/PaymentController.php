<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Client;
use App\Models\Investor;
use App\Models\Subcontractor;
use App\Models\BankAccount; // افترض أن لديك هذا المودل
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaymentController extends Controller
{
    /**
     * عرض قائمة بكل القيود اليومية (الدفعات)
     */
    public function index(Request $request)
    {
        $query = Payment::with('payable')->orderBy('payment_date', 'desc');

        if ($request->filled('search_payable')) {
            $searchTerm = $request->search_payable;
            $query->whereHasMorph('payable', [Client::class, Investor::class, Subcontractor::class], function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('unique_id', 'like', "%{$searchTerm}%");
            });
        }
        if ($request->filled('payment_type')) { $query->where('type', $request->payment_type); }
        if ($request->filled('start_date')) { $query->whereDate('payment_date', '>=', $request->start_date); }
        if ($request->filled('end_date')) { $query->whereDate('payment_date', '<=', $request->end_date); }

        $payments = $query->paginate(15);
        return view('dashboard.payments.index', compact('payments'));
    }

    /**
     * عرض نموذج إنشاء قيد جديد
     */
    public function create()
    {
        $bankAccounts = BankAccount::with('bank')->get() ?? [];
        return view('dashboard.payments.create', compact('bankAccounts'));
    }

    /**
     * حفظ القيد الجديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'payable_type' => 'required|string|in:Client,Investor,Subcontractor',
            'payable_id' => 'required|integer',
            'type' => 'required|in:in,out',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
            'method' => 'required|in:cash,bank_transfer,check',
            'notes' => 'nullable|string',
            'delivered_by' => 'required_if:method,cash|nullable|string',
            'received_by' => 'required_if:method,cash|nullable|string',
            'check_number' => 'required_if:method,check|nullable|string',
            'due_date' => 'required_if:method,check|nullable|date',
            'check_owner' => 'required_if:method,check|nullable|string',
            'sender_bank_account_id' => 'required_if:method,bank_transfer|nullable|exists:bank_accounts,id',
            'receiver_bank_account_id' => 'required_if:method,bank_transfer|nullable|exists:bank_accounts,id',
            'transaction_reference' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                Payment::create([
                    'payable_type' => "App\\Models\\" . $validatedData['payable_type'],
                    'payable_id' => $validatedData['payable_id'],
                    'amount' => $validatedData['amount'],
                    'currency' => $validatedData['currency'],
                    'exchange_rate' => $validatedData['exchange_rate'],
                    'amount_ils' => $validatedData['amount'] * $validatedData['exchange_rate'],
                    'type' => $validatedData['type'],
                    'method' => $validatedData['method'],
                    'payment_date' => $validatedData['payment_date'],
                    'notes' => $validatedData['notes'],
                    'delivered_by' => $validatedData['delivered_by'] ?? null,
                    'received_by' => $validatedData['received_by'] ?? null,
                    'check_number' => $validatedData['check_number'] ?? null,
                    'due_date' => $validatedData['due_date'] ?? null,
                    'check_owner' => $validatedData['check_owner'] ?? null,
                    'sender_bank_account_id' => $validatedData['sender_bank_account_id'] ?? null,
                    'receiver_bank_account_id' => $validatedData['receiver_bank_account_id'] ?? null,
                    'transaction_reference' => $validatedData['transaction_reference'] ?? null,
                ]);
            });
            return redirect()->route('dashboard.payments.index')->with('success', "تم تسجيل القيد بنجاح.");
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'فشل تسجيل القيد: ' . $e->getMessage());
        }
    }

    /**
     * [AJAX] دالة لجلب الكيانات (عملاء، مستثمرين، مقاولين)
     */
    public function getPayables(Request $request)
    {
        $type = $request->query('type');
        if (!in_array($type, ['Client', 'Investor', 'Subcontractor'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }
        $modelClass = "App\\Models\\" . $type;
        $data = $modelClass::select('id', 'name', 'unique_id')->get();
        return response()->json($data);
    }

    /**
     * [AJAX] دالة لجلب تفاصيل كيان معين
     */
   public function getPayableContracts(Request $request)
{
    $request->validate([
        'payable_id' => 'required|integer',
        'payable_type' => 'required|string',
    ]);

    $modelName = "App\\Models\\" . $request->payable_type;

    if (!class_exists($modelName)) {
        return response()->json(['contracts' => []]);
    }

    $payable = $modelName::find($request->payable_id);

    if (!$payable) {
        return response()->json(['contracts' => []]);
    }

    // جلب العقود المرتبطة بالكيان
    $contracts = $payable->contracts()->select('id', 'investment_amount')->get();

    return response()->json(['contracts' => $contracts]);
}
}
