<?php

namespace App\Exports\Excel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class StatisticAttendanceExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $assign;
    protected $subject;
    protected $students;

    public function __construct($assign, $subject, $students)
    {
        $this->assign = $assign;
        $this->subject = $subject;
        $this->students = $students;
    }

    /**
    * @return \Illuminate\Support\View
    */
    public function view(): View
    {
        return view('admins.statistics.export_excel', [
            'students' => $this->students, 
            'subject' => $this->subject,
            'assign' => $this->assign
        ]);
    }

     /**
     * @return string
     */
    public function title(): string
    {
        $grade = $this->assign->grade;
        return $grade->name 
                . "-" 
                . $this->subject->name;
    }
}
