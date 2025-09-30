<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectTransfer;

class ProjectTransferController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'id_number' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'from_project_id' => ['required', 'integer', 'different:to_project_id'],
            'to_project_id' => ['required', 'integer'],
            'currency' => ['required', 'string', 'max:10'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ]);

        ProjectTransfer::create($validated);

        return back()->with('success', 'تم حفظ تحويل المشاريع بنجاح');
    }
}
