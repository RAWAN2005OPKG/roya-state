<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Check;
use App\Models\Project;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckController extends Controller
{
    public function index(Request $request)
    {
        $query = Check::with(['project', 'depositBankAccount.bank'])->latest();

        // تطبيق الفلاتر
        if ($request->filled('search')) {
            $query->where('check_number', 'like', "%{$request->search}%")
                  ->orWhere('party_name', 'like', "%{$request->search}%");
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('due_date_from')) $query->where('due_date', '>=', $request->due_date_from);
        if ($request->filled('due_date_to')) $query->where('due_date', '<=', $request->due_date_to);

        $checks = $query->paginate(15)->appends($request->query());
        return view('dashboard.checks.index', compact('checks'));
    }

    public function create()
    {
        $projects = Project::select('id', 'name')->get();
        $bankAccounts = BankAccount::with('bank')->where('is_active', true)->get();
        return view('dashboard.checks.create', compact('projects', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateCheck($request);
        $validatedData['amount_ils'] = $validatedData['amount'] * $validatedData['exchange_rate'];

        Check::create($validatedData);
        return redirect()->route('dashboard.checks.index')->with('success', 'تم تسجيل الشيك بنجاح.');
    }

    public function show(Check $check)
    {
        return view('dashboard.checks.show', compact('check'));
    }

    public function edit(Check $check)
    {
        $projects = Project::select('id', 'name')->get();
        $bankAccounts = BankAccount::with('bank')->where('is_active', true)->get();
        return view('dashboard.checks.edit', compact('check', 'projects', 'bankAccounts'));
    }

    public function update(Request $request, Check $check)
    {
        $validatedData = $this->validateCheck($request, $check->id);
        $validatedData['amount_ils'] = $validatedData['amount'] * $validatedData['exchange_rate'];

        $check->update($validatedData);
        return redirect()->route('dashboard.checks.index')->with('success', 'تم تحديث الشيك بنجاح.');
    }

    public function destroy(Check $check)
    {
        $check->delete();
        return redirect()->route('dashboard.checks.index')->with('success', 'تم نقل الشيك إلى سلة المهملات.');
    }

    /**
     * [الجديد] دالة لتغيير حالة الشيك (صرف، إرجاع، إلغاء)
     */
    public function updateStatus(Request $request, Check $check)
    {
        $request->validate(['status' => 'required|in:cashed,returned,cancelled']);
        $newStatus = $request->status;

        try {
            DB::transaction(function () use ($check, $newStatus) {
                // منطق تحديث الأرصدة بناءً على تغيير الحالة
                if ($newStatus == 'cashed') {
                    if ($check->type == 'receivable' && $check->deposit_bank_account_id) {
                        // شيك قبض تم صرفه: زيادة رصيد حساب الإيداع
                        $account = BankAccount::find($check->deposit_bank_account_id);
                        $account->current_balance += $check->amount_ils; // افترض أن الأرصدة بالشيكل
                        $account->save();
                    } elseif ($check->type == 'payable' && $check->payment_bank_account_id) {
                        // شيك دفع تم صرفه: خصم من رصيد حساب الدفع
                        $account = BankAccount::find($check->payment_bank_account_id);
                        $account->current_balance -= $check->amount_ils;
                        $account->save();
                    }
                }
                // يمكنك إضافة منطق معاكس إذا تم التراجع عن الصرف

                $check->update(['status' => $newStatus]);
            });
            return back()->with('success', 'تم تحديث حالة الشيك بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث الحالة: ' . $e->getMessage());
        }
    }

    private function validateCheck(Request $request, $checkId = null)
    {
        return $request->validate([
            'check_number' => 'required|string|unique:checks,check_number,' . $checkId,
            'type' => 'required|in:payable,receivable',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'party_name' => 'required|string|max:255',
            'party_phone' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'exchange_rate' => 'required|numeric',
            'bank_name' => 'required|string',
            'deposit_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'payment_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'project_id' => 'nullable|exists:projects,id',
            'notes' => 'nullable|string',
        ]);
    }
}
