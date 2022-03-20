<?php

namespace Database\Seeders;

use Database\Seeders\AdminSeeder;
use Database\Seeders\AssignSeeder;
use Database\Seeders\AttendanceDetailSeeder;
use Database\Seeders\AttendanceSeeder;
use Database\Seeders\ClassRoomSeeder;
use Database\Seeders\GradeSeeder;
use Database\Seeders\LessonSeeder;
use Database\Seeders\ScheduleSeeder;
use Database\Seeders\StudentSeeder;
use Database\Seeders\SubjectSeeder;
use Database\Seeders\TeacherSeeder;
use Database\Seeders\YearSchoolSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Admin::factory(10)->create();
        // Teacher::factory(10)->create();
        // YearSchool::factory(10)->create();
        // ClassRoom::factory(10)->create();
        // Lesson::factory(4)->create();
        // Subject::factory(10)->create();
        // Grade::factory(10)->create();
        // Student::factory(100)->create();
        // Assign::factory(50)->create();
        // Schedule::factory(50)->create();
        // Attendance::factory(100)->create();
        // AttendanceDetail::factory(500)->create();
        
        $this->call([
            AdminSeeder::class,
            TeacherSeeder::class,
            YearSchoolSeeder::class,
            ClassRoomSeeder::class,
            LessonSeeder::class,
            SubjectSeeder::class,
            GradeSeeder::class,
            StudentSeeder::class,
            // AssignSeeder::class,
            // ScheduleSeeder::class,
            // AttendanceSeeder::class,
            // AttendanceDetailSeeder::class
        ]);
    }
}
