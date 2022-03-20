<?php

namespace App\Models;

use App\Models\AttendanceDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function attendanceDetails () {
        return $this->hasMany(AttendanceDetail::class, 'id_attendance');
    }
}
