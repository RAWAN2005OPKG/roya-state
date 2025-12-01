<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Check;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Check::latest();

        if ($request->filled('search')) {
            $query->where('check_number', 'like', "%{$request->search}%")
                  ->orWhere('holder_name', 'like', "%{$request->search}%");
        }

        $checks = $query->paginate(15);

        return view('dashboard.checks.index', compact('checks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.checks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'check_number' => 'required|string|unique:checks,check_number',
            'type' => 'required|in:incoming,outgoing',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string',
            'due_date' => 'required|date',
            'holder_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:in_wallet,cashed,returned',
        ]);

        Check::create($validatedData);

        return redirect()->route('dashboard.checks.index')->with('success', 'تمت إضافة الشيك بنجاح.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Check $check)
    {
        return view('dashboard.checks.edit', compact('check'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Check $check)
    {
        $validatedData = $request->validate([
            'check_number' => 'required|string|unique:checks,check_number,' . $check->id,
            'type' => 'required|in:incoming,outgoing',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string',
            'due_date' => 'required|date',
            'holder_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:in_wallet,cashed,returned',
        ]);

        $check->update($validatedData);

        return redirect()->route('dashboard.checks.index')->with('success', 'تم تعديل الشيك بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Check $check)
    {
        $check->delete();
        return redirect()->route('dashboard.checks.index')->with('success', 'تم حذف الشيك بنجاح.');
    }
}
