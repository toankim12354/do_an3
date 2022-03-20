<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\YearSchool;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GradeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Grade::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->numerify('BKD##K##'),
            'id_year_school' => YearSchool::inRandomOrder()->first()->id,
            'created_at' => $this->faker->dateTime($max = 'now'),
            'updated_at' => $this->faker->dateTime($max = 'now')
        ];
    }
}
