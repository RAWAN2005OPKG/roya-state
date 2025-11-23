<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FundTransfer;
use App\Models\CashSafe;
use App\Models\BankAccount; // افترض أن لديك مودل للحسابات البنكية
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundTransferController extends Controller
{
    public function index(Request $request)
    {
        $transfers = FundTransfer::latest()->paginate(20);
        $cashSafes = CashSafe::where('is_active', true)->get();
        $bankAccounts = BankAccount::where('is_active', true)->get(); // افترض وجود مودل للحسابات البنكية

        return view('dashboard.transfers.funds.index', compact('transfers', 'cashSafes', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string',
            'from_account' => 'required|string',
            'to_account' => 'required|string|different:from_account',
            'notes' => 'nullable|string',
        ]);

        list($from_type, $from_id) = explode('-', $request->from_account);
        list($to_type, $to_id) = explode('-', $request->to_account);

        // يمكنك هنا إضافة منطق للتحقق من الرصيد قبل التحويل

        FundTransfer::create([
            'date' => $request->date,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'from_type' => $from_type,
            'from_id' => $from_id,
            'to_type' => $to_type,
            'to_id' => $to_id,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'تم تسجيل عملية التحويل بنجاح.');
    }
}
