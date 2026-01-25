<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cheque;
use Illuminate\Http\Request;
use Throwable;

class ChequeController extends Controller
{
    public function index(Request $request)
    {
        $query = Cheque::with('payable');
        // يمكنك إضافة فلاتر بحث هنا لاحقاً

        $cheques = $query->latest('due_date')->paginate(20);

        // حسابات الملخص المالي
        $stats = [
            'pending_inbound' => Cheque::where('type', 'inbound')->where('status', 'pending')->sum('amount'),
            'pending_outbound' => Cheque::where('type', 'outbound')->where('status', 'pending')->sum('amount'),
            'collected_total' => Cheque::where('type', 'inbound')->where('status', 'collected')->sum('amount'),
            'bounced_total' => Cheque::where('type', 'inbound')->where('status', 'bounced')->sum('amount'),
        ];

        return view('dashboard.cheques.index', compact('cheques', 'stats'));
    }

    public function create()
    {
        return view('dashboard.cheques.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cheque_number' => 'required|string|unique:cheques,cheque_number',
            'type' => 'required|in:inbound,outbound',
            'receipt_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'bank_name' => 'required|string',
            'payable_type' => 'required|string|in:Client,Investor,Subcontractor',
            'payable_id' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        try {
            $modelClass = "App\\Models\\" . $validated['payable_type'];
            if (!class_exists($modelClass) || !$modelClass::find($validated['payable_id'])) {
                throw new \Exception("الكيان المرتبط غير موجود.");
            }
            $validated['payable_type'] = $modelClass;
            Cheque::create($validated);
            return redirect()->route('dashboard.cheques.index')->with('success', 'تم حفظ الشيك بنجاح.');
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'فشل حفظ الشيك: ' . $e->getMessage());
        }
    }

    public function edit(Cheque $cheque)
    {
        return view('dashboard.cheques.edit', compact('cheque'));
    }

    public function update(Request $request, Cheque $cheque)
    {
        // يمكنك إضافة منطق التحديث هنا
        return redirect()->route('dashboard.cheques.index')->with('success', 'تم تحديث الشيك بنجاح.');
    }

    // دالة لتغيير حالة الشيك (مثلاً، من قيد الانتظار إلى محصّل)
    public function updateStatus(Request $request, Cheque $cheque)
    {
        $request->validate(['status' => 'required|in:pending,collected,bounced']);
        $cheque->update(['status' => $request->status]);
        return back()->with('success', 'تم تحديث حالة الشيك بنجاح.');
    }
}
