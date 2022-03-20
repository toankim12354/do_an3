<?php

namespace App\Models;

use App\Models\Student;
use App\Models\YearSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function students()
    {
        return $this->hasMany(Student::class, 'id_grade');
    }

    public function yearSchool()
    {
        return $this->belongsTo(YearSchool::class, 'id_year_school');
    }

    public function assigns()
    {
        return $this->hasMany(Assign::class, 'id_grade');
    }
}
