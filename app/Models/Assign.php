<?php

namespace App\Models;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Assign extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_grade",
        "id_subject",
        "id_teacher",
        "status",
        "time_done",
        "start_at"
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'id_grade');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'id_subject');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'id_teacher');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'id_assign');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'id_assign');
    }

    public function findAttendanceByDate($createdAt)
    {
        return $this->attendances
                    ->where('created_at', '>=', $createdAt)
                    ->first();
    }

    public function findScheduleByDay($day)
    {
        return $this->schedules->where('day', $day)->first();
    }

    // convert d-m-Y to Y-m-d when set dob Attribute
    public function setStartAtAttribute($value)
    {
        $this->attributes['start_at'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function getStartAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getInfoAttribute()
    {
        $subjectDuration = $this->subject->duration;

        $timeDonePreviousMonths = Attendance::whereRaw(
                "DATE(created_at) < DATE_FORMAT(NOW(), '%Y-%m-01')"
            )->where('id_assign', $this->id)->sum('time');

        $timeDoneCurrentMonth = Attendance::whereRaw(
                'MONTH(created_at) = MONTH(CURDATE()) 
                AND YEAR(created_at) = YEAR(CURDATE())'
            )->where('id_assign', $this->id)->sum('time');

        $allTimeDone = $timeDonePreviousMonths + $timeDoneCurrentMonth;
        $timeRemain  = $subjectDuration - $allTimeDone;
        $timeRemain  = $timeRemain > 0 ? $timeRemain : 0;

        return [
            'subjectDuration'        => $subjectDuration,
            'timeDonePreviousMonths' => $timeDonePreviousMonths,
            'timeDoneCurrentMonth'   => $timeDoneCurrentMonth,
            'timeRemain'             => $timeRemain
        ];
    }

    public function getHistoryAttendance()
    {
        $students = $this->grade->students
                                ->where('status', '1')
                                ->sortBy('name');

        foreach ($students as $key => $student) {
            $students[$key]->fetchInfoAttendance($this);
        }
        
        $attendances = $this->attendances->sortBy('created_at');
        $statuses    = [];

        foreach ($students as $key => $student) {
            foreach ($attendances as $key => $attendance) {
                $status = $attendance->attendanceDetails
                                     ->where('id_student', $student->id)
                                     ->first()->status ?? null;

                $statuses[$attendance->id][$student->id] = $status;
            }
        }
        
        return (object) [
            'attendances' => $attendances,
            'students'    => $students,
            'statuses'    => $statuses
        ];
    }
}
