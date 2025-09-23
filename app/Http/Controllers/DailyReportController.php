<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManualReport;

class DailyReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_date' => ['required', 'date'],
            'achievements' => ['nullable', 'string'],
            'issues' => ['nullable', 'string'],
            'decisions' => ['nullable', 'string'],
        ]);

        ManualReport::updateOrCreate(
            ['report_date' => $validated['report_date']],
            [
                'achievements' => $validated['achievements'] ?? null,
                'issues' => $validated['issues'] ?? null,
                'decisions' => $validated['decisions'] ?? null,
            ]
        );

        return back()->with('success', 'تم حفظ التقرير اليومي بنجاح');
    }
}
