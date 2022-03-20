<?php

namespace Database\Seeders;

use App\Models\YearSchool;
use Illuminate\Database\Seeder;

class YearSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        YearSchool::factory(10)->create();
    }
}
