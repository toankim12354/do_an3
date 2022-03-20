<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Schedule;

class Lesson extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function getStartAttribute($value) {
        return Carbon::createFromFormat('H:i:s', $value)
                      ->format('H:i');
    }

    public function getEndAttribute($value) {
        return Carbon::createFromFormat('H:i:s', $value)
                      ->format('H:i');
    }

    public function schedules() {
        return $this->hasMany(Schedule::class, 'id_lesson');
    }

    public function getTimeAttribute()
    {
        return (strtotime($this->end) - strtotime($this->start)) / 3600;
    }
}
