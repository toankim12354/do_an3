<?php

namespace Database\Factories;

use App\Models\YearSchool;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class YearSchoolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = YearSchool::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->numerify('K##'),
            'created_at' => $this->faker->dateTime($max = 'now'),
            'updated_at' => $this->faker->dateTime($max = 'now'),
        ];
    }
}
