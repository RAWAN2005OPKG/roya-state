<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        $query = Alert::query();
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($search) {
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
        }

        $alerts = $query->orderBy($sortBy, $sortOrder)->paginate(15);

        return view('dashboard.alerts.index', compact('alerts', 'search', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        // يمكنك إنشاء واجهة لإضافة تنبيه يدوي إذا أردت
        return view('dashboard.alerts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:cheque_due,contract_expiry,payment_due,general',
            'priority' => 'required|in:high,medium,low',
            'due_date' => 'nullable|date',
        ]);
        $validated['status'] = 'active'; // الحالة الافتراضية

        Alert::create($validated);
        return redirect()->route('dashboard.alerts.index')->with('success', 'تم إنشاء التنبيه بنجاح.');
    }

    public function update(Request $request, Alert $alert)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,dismissed,resolved',
        ]);
        $alert->update($validated);
        return back()->with('success', 'تم تحديث حالة التنبيه.');
    }

    public function destroy(Alert $alert)
    {
        $alert->delete();
        return back()->with('success', 'تم حذف التنبيه.');
    }
}
