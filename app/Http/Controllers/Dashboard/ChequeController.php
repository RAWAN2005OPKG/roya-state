<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cheque;
use App\Models\User;
use App\Notifications\ChequeStatusChangedNotification;
use Illuminate\Support\Facades\Notification;
class ChequeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cheque_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'type' => ['required', 'in:incoming,outgoing'],
            'cheque_number' => ['nullable', 'string', 'max:255'],
            'transfer_number' => ['nullable', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'holder_name' => ['nullable', 'string', 'max:255'],
            'payer_id_number' => ['nullable', 'string', 'max:100'],
            'client_phone' => ['nullable', 'string', 'max:50'],
            'beneficiary_name' => ['nullable', 'string', 'max:255'],
            'project_name' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'other_bank_name' => ['nullable', 'string', 'max:255'],
            'bank_branch' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'operator' => ['nullable', 'string', 'max:255'],
            'transfer_details' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:in_wallet,cashed,returned'],
        ]);

        Cheque::create($validated);

 if ($oldStatus !== $newStatus) {
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new ChequeStatusChangedNotification($check, $newStatus));
            }
        }
        return redirect()->route('dashboard.cheques.index')->with('success', 'تمت إضافة الشيك بنجاح!');
    }
}
