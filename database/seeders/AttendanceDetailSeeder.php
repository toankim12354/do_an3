<?php

namespace Database\Seeders;

use App\Models\AttendanceDetail;
use Illuminate\Database\Seeder;

class AttendanceDetailSeeder extends Seeder
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
            AttendanceDetail::factory(500)->create();
        } catch (\Exception $e) {
           if ($this->try_time === 5) {
                return;
            }

            $this->try_time++;
            $this->run();
        }
    }
}
