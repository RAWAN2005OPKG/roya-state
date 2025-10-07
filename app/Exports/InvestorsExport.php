<?php

namespace App\Exports;

use App\Models\Investor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvestorsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        return Investor::all();
    }

    /**
     * تحديد الأعمدة التي سيتم تصديرها لكل صف.
     *
     * @param mixed $investor
     * @return array
     */
    public function map($investor): array
    {
        return [
            $investor->name,
            $investor->id_number,
            $investor->phone,
            $investor->email,
            $investor->address,
            $investor->notes,
            $investor->created_at->format('Y-m-d'), // تنسيق تاريخ الإنشاء
        ];
    }

    /**
     * تحديد عناوين الأعمدة في ملف الإكسل.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'الاسم',
            'رقم الهوية',
            'الجوال',
            'البريد الإلكتروني',
            'العنوان',
            'ملاحظات',
            'تاريخ الإضافة',
        ];
    }
}
