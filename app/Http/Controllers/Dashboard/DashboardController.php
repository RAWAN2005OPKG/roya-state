<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use App\Models\BankTransaction;
use App\Models\Cheque;
use App\Models\FundTransfer;
use App\Models\ReceiptVoucher;
use App\Models\PaymentVoucher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // جمع البيانات من الكاش
        $cashTransactions = CashTransaction::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'transaction_type' => 'كاش - ' . ($item->type == 'deposit' ? 'إيداع' : ($item->type == 'withdrawal' ? 'سحب' : 'مسحوبات شخصية')),
                'amount' => $item->type == 'deposit' ? $item->amount : -$item->amount,
                'amount_class' => $item->type == 'deposit' ? 'status-deposit' : 'status-withdrawal',
                'currency' => $item->currency,
                'project_name' => $item->project_name,
                'beneficiary' => $item->beneficiary,
                'source' => $item->source,
                'details' => $item->details,
                'status' => '-',
            ];
        });

        // جمع البيانات من البنك
        $bankTransactions = BankTransaction::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'transaction_type' => 'بنك - ' . ($item->type == 'deposit' ? 'إيداع' : ($item->type == 'withdrawal' ? 'سحب' : ($item->type == 'transfer' ? 'حوالة' : 'مسحوبات شخصية'))),
                'amount' => $item->type == 'deposit' ? $item->amount : -$item->amount,
                'amount_class' => $item->type == 'deposit' ? 'status-deposit' : 'status-withdrawal',
                'currency' => $item->currency,
                'project_name' => $item->project_name,
                'beneficiary' => $item->beneficiary_name,
                'source' => $item->source,
                'details' => $item->details,
                'status' => '-',
            ];
        });

        // جمع بيانات الشيكات
        $cheques = Cheque::all()->map(function ($item) {
            $statusText = $item->status == 'in_wallet' ? 'في المحفظة' : ($item->status == 'cashed' ? 'تم صرفه' : 'مرتجع');
            return [
                'id' => $item->id,
                'date' => $item->due_date ?? $item->cheque_date,
                'transaction_type' => 'شيك - ' . ($item->type == 'incoming' ? 'وارد' : 'صادر'),
                'amount' => $item->amount,
                'amount_class' => 'status-deposit',
                'currency' => $item->currency,
                'project_name' => $item->project_name,
                'beneficiary' => $item->beneficiary_name,
                'source' => $item->owner_name,
                'details' => $item->notes,
                'status' => $statusText,
            ];
        });

        // جمع بيانات تحويلات الأموال
        $fundTransfers = FundTransfer::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'transaction_type' => 'تحويل أموال',
                'amount' => $item->amount,
                'amount_class' => 'status-deposit',
                'currency' => $item->currency,
                'project_name' => null,
                'beneficiary' => $item->to_account,
                'source' => $item->from_account,
                'details' => $item->notes,
                'status' => '-',
            ];
        });


        $receiptVouchers = ReceiptVoucher::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->transaction_date,
                'transaction_type' => 'سند قبض - ' . ($item->payment_method == 'cash' ? 'نقدي' : ($item->payment_method == 'bank_transaction' ? 'حوالة' : 'شيك')),
                'amount' => $item->amount,
                'amount_class' => 'status-deposit',
                'currency' => $item->currency,
                'project_name' => $item->project_id,
                'beneficiary' => $item->receiver_name,
                'source' => null,
                'details' => $item->purpose_description,
                'status' => '-',
            ];
        });


        $paymentVouchers = PaymentVoucher::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->transaction_date,
                'transaction_type' => 'سند صرف - ' . ($item->payment_method == 'cash' ? 'نقدي' : ($item->payment_method == 'bank_transaction' ? 'حوالة' : 'شيك')),
                'amount' => -$item->amount,
                'amount_class' => 'status-withdrawal',
                'currency' => $item->currency,
                'project_name' => $item->project_id,
                'beneficiary' => $item->receiver_name,
                'source' => null,
                'details' => $item->purpose_description,
                'status' => '-',
            ];
        });

        // دمج كل الحركات
        $transactions = $cashTransactions
            ->merge($bankTransactions)
            ->merge($cheques)
            ->merge($fundTransfers)
            ->merge($receiptVouchers)
            ->merge($paymentVouchers)
            ->sortByDesc('date');

        return view('prbancascheq', compact('transactions'));
    }
}
