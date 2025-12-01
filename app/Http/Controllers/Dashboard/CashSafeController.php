<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CashSafe;
use Illuminate\Http\Request;

class CashSafeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cashSafes = CashSafe::latest()->paginate(10);
        return view('dashboard.cash_safes.index', compact('cashSafes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:cash_safes,name',
            'initial_balance' => 'required|numeric|min:0',
        ]);

        $validatedData['balance'] = $validatedData['initial_balance']; // الرصيد الحالي يبدأ بالافتتاحي

        CashSafe::create($validatedData);

        return redirect()->route('dashboard.cash-safes.index')->with('success', 'تمت إضافة الخزينة بنجاح.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashSafe $cashSafe)
    {
        return view('dashboard.cash_safes.edit', compact('cashSafe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashSafe $cashSafe)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:cash_safes,name,' . $cashSafe->id,
            'is_active' => 'required|boolean',
        ]);

        $cashSafe->update($validatedData);

        return redirect()->route('dashboard.cash-safes.index')->with('success', 'تم تعديل الخزينة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashSafe $cashSafe)
    {
        $cashSafe->delete();
        return redirect()->route('dashboard.cash-safes.index')->with('success', 'تم حذف الخزينة بنجاح.');
    }
}
