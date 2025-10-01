<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;

class ContractController extends Controller
{
    /**
     * عرض صفحة إنشاء عقد جديد
     */
    public function create()
    {
        return view('dashboard.contracts');
    }

    /**
     * حفظ عقد جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // بيانات العقد
            'contract_id' => ['required', 'string', 'max:255', 'unique:contracts,contract_id'],
            'signing_date' => ['required', 'date'],
            'status' => ['nullable', 'in:active,draft'],

            // بيانات المستثمر
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_phone' => ['required', 'string', 'max:50'],
            'client_alt_phone' => ['nullable', 'string', 'max:50'],
            'client_id_number' => ['required', 'string', 'max:100'],

            // بيانات العقار
            'property_type' => ['nullable', 'string', 'max:255'],
            'property_location' => ['nullable', 'string', 'max:255'],

            // التفاصيل المالية
            'investment_amount' => ['required', 'numeric', 'min:0'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,bank_transaction,check'],
            'apartment_price' => ['nullable', 'numeric', 'min:0'],
            'first_payment_date' => ['nullable', 'date'],
            'down_payment_initial' => ['nullable', 'numeric', 'min:0'],
            'down_payment_other' => ['nullable', 'numeric', 'min:0'],
            'profit_percentage' => ['nullable', 'numeric', 'min:0'],
            'remaining_amount' => ['nullable', 'numeric', 'min:0'],

            // تفاصيل الدفع النقدي
            'cash_amount' => ['nullable', 'numeric', 'min:0'],
            'cash_receipt_number' => ['nullable', 'string', 'max:255'],

            // تفاصيل التحويل البنكي
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'transfer_date' => ['nullable', 'date'],

            // تفاصيل الشيك
            'check_number' => ['nullable', 'string', 'max:255'],
            'check_amount' => ['nullable', 'numeric', 'min:0'],
            'check_holder' => ['nullable', 'string', 'max:255'],
            'check_bank' => ['nullable', 'string', 'max:255'],
            'check_bank_branch' => ['nullable', 'string', 'max:255'],
            'check_due_date' => ['nullable', 'date'],
            'check_receipt_date' => ['nullable', 'date'],
        ]);

        try {
            // إنشاء العقد
            Contract::create($validated);

            return redirect()->route('dashboard.client-payments.index')
                           ->with('success', 'تم حفظ العقد بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ أثناء حفظ العقد: ' . $e->getMessage());
        }
    }

    /**
     * عرض قائمة العقود (اختياري لو كنتِ عايزة صفحة العقود النشطة)
     */
    public function index()
    {
        $contracts = Contract::all();
        return view('dashboard.client-payments.index', compact('contracts'));
    }
}
