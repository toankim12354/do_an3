<?php

namespace App\Exports\Excel;

use App\Models\Assign;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ScheduleExport implements FromView, ShouldAutoSize, WithTitle
{
    private $id_assign;

    public function __construct($id_assign)
    {
        $this->id_assign = $id_assign;
    }

    /**
    * @return view
    */
    public function view(): view
    {
        //
        $assign = Assign::find($this->id_assign);
        return view('admins.schedules.export_excel', [
            'assign' => $assign
        ]);
    }

    public function title(): string
    {
        $assign = Assign::find($this->id_assign);
        return $assign->grade->name."-".$assign->subject->name;
    }
}
