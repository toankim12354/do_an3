<?php

namespace Database\Factories;

use App\Models\Assign;
use App\Models\ClassRoom;
use App\Models\Lesson;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_assign'     => Assign::inRandomOrder()->first()->id,
            'id_class_room' => ClassRoom::inRandomOrder()->first()->id,
            'id_lesson'     => Lesson::inRandomOrder()->first()->id,
            'day'           => $this->faker->numberBetween(1, 6),
            'day_finish'    => date('Y-m-d H:i:s', rand(1000111222,1262055681)),
            'created_at'    => $this->faker->dateTime($max = 'now'),
            'updated_at'    => $this->faker->dateTime($max = 'now')
        ];
    }
}
