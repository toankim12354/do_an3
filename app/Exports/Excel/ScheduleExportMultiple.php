<?php

namespace App\Exports\Excel;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Excel\ScheduleExport as ExportSignal;

class ScheduleExportMultiple implements WithMultipleSheets
{
    use Exportable;

    protected $id_assigns;

    public function __construct($id_assigns)
    {
        $this->id_assigns = $id_assigns;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $id_assigns = $this->id_assigns;

        for ($i = 0; $i < count($this->id_assigns); $i++) {
            $sheets[] = new ExportSignal((int)$id_assigns[$i]);
        }

        return $sheets;
    }
}
