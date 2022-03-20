<?php

namespace Database\Factories;

use App\Models\Assign;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Assign::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_grade'   => Grade::inRandomOrder()->first()->id,
            'id_subject' => Subject::inRandomOrder()->first()->id,
            'id_teacher' => Teacher::inRandomOrder()->first()->id,
            'start_at'   => $this->faker->date(),
            'time_done'  => $this->faker->numberBetween(20, 100),
            'created_at' => $this->faker->dateTime($max = 'now'),
            'updated_at' => $this->faker->dateTime($max = 'now')
        ];
    }
}
