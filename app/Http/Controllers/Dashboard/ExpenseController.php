<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index(){
        return view("dashboard.expenses");
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'            => ['required', 'date'],
            'payee'           => ['required', 'string', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:50'],
            'job'             => ['nullable', 'string', 'max:100'],
            'id_number'       => ['nullable', 'string', 'max:50'],
            'project_id'      => ['nullable', 'integer'],
            'amount'          => ['required', 'numeric', 'min:0'],
            'currency'        => ['required', 'string', 'max:10'],
            'payment_method'  => ['required', 'string', 'max:50'],
            'payment_source'  => ['required', 'string', 'max:50'],
            'cash_receiver'        => ['nullable', 'string', 'max:100'],
            'cash_receiver_other'  => ['nullable', 'string', 'max:100'],
            'receiver_job'         => ['nullable', 'string', 'max:100'],
            'sender_bank'          => ['nullable', 'string', 'max:100'],
            'other_sender_bank'    => ['nullable', 'string', 'max:100'],
            'sender_branch'        => ['nullable', 'string', 'max:100'],
            'receiver_bank'        => ['nullable', 'string', 'max:100'],
            'other_receiver_bank'  => ['nullable', 'string', 'max:100'],
            'receiver_branch'      => ['nullable', 'string', 'max:100'],
            'transaction_id'       => ['nullable', 'string', 'max:100'],
            'check_number'         => ['nullable', 'string', 'max:100'],
            'check_owner'          => ['nullable', 'string', 'max:100'],
            'check_holder'         => ['nullable', 'string', 'max:100'],
            'check_due_date'       => ['nullable', 'date'],
            'check_receive_date'   => ['nullable', 'date'],
            'notes'                => ['nullable', 'string'],
        ]);

        Expense::create($validated);

        return back()->with('success', 'تم حفظ المصروف بنجاح');
    }
}
