<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    private $try_time = 0;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            Schedule::factory(10)->create();
        } catch (\Exception $e) {
            if ($this->try_time === 5) {
                return;
            }

            $this->try_time++;
            $this->run();
        }
    }
}
