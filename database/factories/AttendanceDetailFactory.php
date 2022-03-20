<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AttendanceDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_attendance' => Attendance::inRandomOrder()->first()->id,
            'id_student'    => Student::inRandomOrder()->first()->id,
            'status'        => $this->faker->numberBetween(0, 3)
        ];
    }
}
