<?php

namespace App\Exports\Excel;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentsExportMultiple implements WithMultipleSheets
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $grades = Grade::all();

        $sheets = [];

        foreach ($grades as $key => $grade) {
            $sheet = new StudentsExport($grade);
            $sheets[] = $sheet;
        }

        return $sheets;
    }
}
