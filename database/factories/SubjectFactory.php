<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
           'name'       => $this->faker->unique()->numerify('MON##'),
           'duration'   => $this->faker->numberBetween(20, 100),
           'created_at' => $this->faker->dateTime($max = 'now'),
           'updated_at' => $this->faker->dateTime($max = 'now')
        ];
    }
}
