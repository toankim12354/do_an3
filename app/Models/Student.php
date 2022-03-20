<?php

namespace App\Models;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Student extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'dob',
        'gender',
        'phone',
        'address',
        'email',
        'password',
        'id_grade',
        'status'
    ];

    protected $hidden = ['password', 'remember_token'];

    // cast geder to string
    public function getGenderAttribute($value)
    {
        return $value ? "Nam" : "Nữ";
    }

    public function grade()
    {
        // dd($this->belongsTo(Grade::class));
        return $this->belongsTo(Grade::class, 'id_grade');   
    }

    // convert Y-m-d to d-m-Y when get dob Attribute
    public function getDobAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value)
                      ->format('d-m-Y');
    }

    // convert d-m-Y to Y-m-d when set dob Attribute
    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function setInfoAttendanceAttribute($value)
    {
        $this->attributes['infoAttendance'] = $value;
    }

    public function fetchInfoAttendance($assign, $mode = 0)
    {   
        $totalTimes = count($assign->attendances);

        $query = DB::table('attendances')
        ->join('attendance_details', 'attendances.id', '=', 'attendance_details.id_attendance')
        ->where('attendances.id_assign', $assign->id)
        ->where('attendance_details.id_student', $this->id)
        ->selectRaw('SUM(IF(attendance_details.status = 0, 1, 0)) as absents, 
            SUM(IF(attendance_details.status = 1, 1, 0)) as presents, 
            SUM(IF(attendance_details.status = 2, 1, 0)) as lates, 
            SUM(IF(attendance_details.status = 3, 1, 0)) as hasReasons');

        $result = (array) $query->get()->toArray()[0];

        foreach ($result as $key => $value) {
            $result[$key] = $value ?? 0;
        }
        
        $missTimes            = $totalTimes - array_sum($result);
        $result['missTimes']  = $missTimes;
        $result['totalTimes'] = $totalTimes;

        // thong ke
        $timeAsAbsents = $result['absents'] + floor($result['lates'] / 2);
        $absentPercent = ($totalTimes > 0) 
                        ? round($timeAsAbsents / $totalTimes * 100) : 0;

        $result['timeAsAbsents'] = $timeAsAbsents;
        $result['absentPercent'] = $absentPercent;
        
        // set attribute infoAttendance
        $this->infoAttendance = (object) $result;

        if ($mode) {
           return (object) $result;
        }
    }
}
