<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // جلب العملاء مع علاقاتهم لتقليل استعلامات قاعدة البيانات
        return Client::with(['contracts.projectUnit'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'الاسم',
            'رقم الهوية',
            'الجوال',
            'الوحدات المشتراة',
            'إجمالي المستحق (ILS)',
            'إجمالي المدفوع (ILS)',
            'الرصيد المتبقي (ILS)',
        ];
    }

    public function map($client): array
    {
        // تحويل قائمة الوحدات إلى نص واحد
        $units = $client->contracts->map(function ($contract) {
            return $contract->projectUnit->unit_number ?? 'N/A';
        })->implode(', ');

        return [
            $client->unique_id,
            $client->name,
            $client->id_number,
            $client->phone,
            $units,
            $client->total_due_ils,
            $client->total_paid_ils,
            $client->remaining_balance,
        ];
    }
}
