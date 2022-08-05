<?php

namespace Database\Seeders;

use App\Models\Technician;
use Illuminate\Database\Seeder;

// sail artisan db:seed --class=TechnicianSeeder
class TechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Technician::factory(10)->create();
    }
}
