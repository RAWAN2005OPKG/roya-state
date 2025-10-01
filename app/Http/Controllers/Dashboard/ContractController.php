<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::all();
        return view('dashboard.new-contract', compact('contracts'));
    }

    public function create()
    {
        return view('dashboard.new-contract');
    }


    public function store(Request $request)
    {

        $validated = $request->validate([


            'contract_id' => ['required', 'string', 'max:255', 'unique:contracts,contract_id'],
            'signing_date' => ['required', 'date'],
            'status' => ['nullable', 'in:active,draft'],


            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_phone' => ['required', 'string', 'max:50'],
            'client_alt_phone' => ['nullable', 'string', 'max:50'],
            'client_id_number' => ['required', 'string', 'max:100'],


            'property_type' => ['nullable', 'string', 'max:255'],
            'property_location' => ['nullable', 'string', 'max:255'],


            'investment_amount' => ['required', 'numeric', 'min:0'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,bank_transaction,check'],
            'apartment_price' => ['nullable', 'numeric', 'min:0'],
            'first_payment_date' => ['nullable', 'date'],
            'down_payment_initial' => ['nullable', 'numeric', 'min:0'],
            'down_payment_other' => ['nullable', 'numeric', 'min:0'],
            'profit_percentage' => ['nullable', 'numeric', 'min:0'],
            'remaining_amount' => ['nullable', 'numeric', 'min:0'],
            'cash_amount' => ['nullable', 'numeric', 'min:0', 'required_if:payment_method,cash'],
            'cash_receipt_number' => ['nullable', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255', 'required_if:payment_method,bank_transaction'],
            'account_number' => ['nullable', 'string', 'max:255', 'required_if:payment_method,bank_transaction'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'transfer_date' => ['nullable', 'date'],
            'check_number' => ['nullable', 'string', 'max:255', 'required_if:payment_method,check'],
            'check_amount' => ['nullable', 'numeric', 'min:0', 'required_if:payment_method,check'],
            'check_holder' => ['nullable', 'string', 'max:255'],
            'check_bank' => ['nullable', 'string', 'max:255'],
            'check_bank_branch' => ['nullable', 'string', 'max:255'],
            'check_due_date' => ['nullable', 'date'],
            'check_receipt_date' => ['nullable', 'date'],
        ]);


        Project::create($data);

        return redirect()->route('dashboard.client-payments')->with('success', 'تم إضافة المشروع بنجاح!');
    }


     # حذف مشروع (وظيفة destroy)

    public function destroy(Project $project)
    {
        $project->delete(); # حذف المشروع من قاعدة البيانات

        # بعد الحذف، قم بإعادة توجيه المستخدم إلى صفحة قائمة المشاريع
        return redirect()->route('dashboard.new-contract')->with('success', 'تم حذف المشروع بنجاح!');
    }
}

