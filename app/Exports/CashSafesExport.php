<?php

namespace App\Exports;

use App\Models\CashSafe;
use Maatwebsite\Excel\Concerns\FromCollection;

class CashSafesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CashSafe::all();
    }
}
