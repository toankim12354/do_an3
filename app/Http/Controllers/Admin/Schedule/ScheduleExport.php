<?php

namespace App\Http\Controllers\Admin\Schedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Excel\ScheduleExport as ExportSignal;
use App\Exports\Excel\ScheduleExportMultiple;

class ScheduleExport extends Controller
{
    //
    public function exportForClass($id)
    {
        return Excel::download(new ExportSignal($id), 'schedule.xlsx');
    }

    public function exportMultipleForClass(Request $request)
    {
        return (new ScheduleExportMultiple($request->id_assigns))->download('schedules.xlsx');
    }
}
