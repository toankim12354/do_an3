<?php

namespace App\Exports\Excel;

use App\Exports\Excel\StatisticAttendanceExport;
use App\Models\Assign;
use App\Models\Grade;
use App\Models\Subject;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StatisticAttendanceExportMultiple implements WithMultipleSheets
{
    use Exportable;

    protected $idGrade;
    protected $idSubjects;
    
    public function __construct($idGrade, $idSubjects)
    {
        $this->idGrade = $idGrade;
        $this->idSubjects = $idSubjects;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $grade = Grade::findOrFail($this->idGrade);

        $students = $grade->students;

        // one subject one sheet
        foreach ($this->idSubjects as $key => $idSubject) {
            $subject = Subject::findOrFail($idSubject);

            $assign = Assign::where('id_grade', $this->idGrade)
                            ->where('id_subject', $idSubject)
                            ->first();

            // make data for one sheet
            $data = [];

            foreach ($students as $key => $student) {
                $data[] = (object) [
                    'id' => $student->id,
                    'code' => $student->code,
                    'name' => $student->name,
                    'infoAttendance' => $student
                                        ->fetchInfoAttendance($assign, 1)
                ];
            }

            // build sheet
            $sheet = new StatisticAttendanceExport($assign, $subject, $data);

            // add sheet to list of sheet
            $sheets[] = $sheet;
        }

        return $sheets;
    }
}
