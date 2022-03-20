<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lesson;
use App\Models\ClassRoom;
use App\Models\Assign;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_assign",
        "id_class_room",
        "day",
        'id_lesson',
        'day_finish'
    ];

    public function lesson () {
        return $this->belongsTo(Lesson::class, 'id_lesson');
    }

    public function classRoom () {
        return $this->belongsTo(ClassRoom::class, 'id_class_room');
    }

    public function assign () {
        return $this->belongsTo(Assign::class, 'id_assign');
    }
}
