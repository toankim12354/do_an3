<?php

namespace App\Exports\Excel;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class StudentsExport implements FromView, ShouldAutoSize, WithTitle
{
    use Exportable;

    private $grade;

    public function __construct($grade)
    {
        $this->grade = $grade;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view() : View
    {
        return view('admins.students.export',[
            'students' => $this->grade->students,
            'grade' => $this->grade
        ]);
    }

    public function title(): string
    {
        return $this->grade->name;
    }
}
