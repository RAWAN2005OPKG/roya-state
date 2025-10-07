<?php

namespace App\Exports;

use App\Models\Investment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvestmentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Investment::with('investor')->get();
    }

    public function map($investment): array
    {
        return [
            $investment->investor->name ?? 'N/A',
            $investment->project,
            $investment->date->format('Y-m-d'),
            $investment->amount,
            $investment->currency,
            $investment->share_percentage,
            $investment->status,
        ];
    }

    public function headings(): array
    {
        return [
            'اسم المستثمر',
            'المشروع',
            'تاريخ الاستثمار',
            'المبلغ',
            'العملة',
            'نسبة الحصة (%)',
            'الحالة',
        ];
    }
}
