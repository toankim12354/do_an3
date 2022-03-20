<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;


class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'       => $this->faker->name(),
            'code'       => $this->faker->unique()->numerify('BKC#####'),
            'dob'        => $this->faker->date('d-m-Y'),
            'gender'     => $this->faker->numberBetween(0, 1),
            'phone'      => $this->faker->unique()->numerify('##########'),
            'address'    => $this->faker->streetAddress(),
            'email'      => $this->faker->unique()->safeEmail(),
            'password'   => Hash::make('11111111'),
            'id_grade'   => Grade::inRandomOrder()->first()->id,
            'created_at' => $this->faker->dateTime($max = 'now'),
            'updated_at' => $this->faker->dateTime($max = 'now')
        ];
    }
}
