<?php

namespace App\Models;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearSchool extends Model
{
    use HasFactory;

    public function grades()
    {
        return $this->hasMany(Grade::class, 'id_year_school');
    }
}
