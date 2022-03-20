<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'       => $this->faker->name(),
            'dob'        => $this->faker->date('d-m-Y'),
            'gender'     => $this->faker->numberBetween(0, 1),
            'phone'      => $this->faker->unique()->numerify('##########'),
            'address'    => $this->faker->streetAddress(),
            'email'      => $this->faker->unique()->safeEmail(),
            'password'   => Hash::make('11111111'),
            'created_at' => $this->faker->dateTime($max = 'now'),
            'updated_at' => $this->faker->dateTime($max = 'now')
        ];
    }
}
