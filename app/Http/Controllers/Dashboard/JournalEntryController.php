<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JournalEntryController extends Controller
{
    /**
     * عرض جميع قيود اليومية
     */
    public function index(Request $request)
    {
        $query = JournalEntry::latest();
        if ($search = $request->input('search')) {
            $query->where('description', 'like', "%{$search}%")->orWhere('id', $search);
        }
        $journalEntries = $query->paginate(20);
        return view('dashboard.journal_entries.index', compact('journalEntries'));
    }

    /**
     * عرض صفحة إنشاء قيد جديد
     */
    public function create()
    {
        $accounts = Account::where('is_active', true)->orderBy('name')->get();
        return view('dashboard.journal_entries.create', compact('accounts'));
    }

    /**
     * تخزين قيد جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:500',
            'items' => 'required|array|min:2',
            'items.*.account_id' => 'required|exists:accounts,id',
            'items.*.debit' => 'nullable|numeric|min:0|required_without:items.*.credit',
            'items.*.credit' => 'nullable|numeric|min:0|required_without:items.*.debit',
        ]);

        $totalDebit = collect($request->items)->sum('debit');
        $totalCredit = collect($request->items)->sum('credit');

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            return back()->withErrors(['balance' => 'القيد غير متوازن! مجموع المدين لا يساوي مجموع الدائن.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $journalEntry = JournalEntry::create($request->only('date', 'description'));
            foreach ($request->items as $item) {
                if (!empty($item['account_id']) && (!empty($item['debit']) || !empty($item['credit']))) {
                    $journalEntry->items()->create([
                        'account_id' => $item['account_id'],
                        'debit' => $item['debit'] ?? 0,
                        'credit' => $item['credit'] ?? 0,
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('dashboard.journal-entries.index')->with('success', 'تم تسجيل القيد بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * عرض قيد معين (للطباعة أو التفاصيل)
     */
    public function show(JournalEntry $journalEntry)
    {
        $journalEntry->load('items.account');
        return view('dashboard.journal_entries.show', compact('journalEntry'));
    }

    /**
     * حذف قيد يومية
     */
    public function destroy(JournalEntry $journalEntry)
    {
        // الحذف هنا يجب أن يكون بحذر شديد في نظام حقيقي
        // قد تحتاج إلى عكس تأثير القيد بدلاً من حذفه
        $journalEntry->delete(); // سيقوم بحذف البنود تلقائياً بسبب onDelete('cascade')
        return redirect()->route('dashboard.journal-entries.index')->with('success', 'تم حذف القيد بنجاح.');
    }
}
