<?php

namespace Database\Seeders;

use App\Models\Assign;
use Illuminate\Database\Seeder;

class AssignSeeder extends Seeder
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
            Assign::factory(50)->create();
        } catch (\Exception $e) {
            if ($this->try_time === 5) {
                return;
            }

            $this->try_time++;
            $this->run();
        }
    }
}
