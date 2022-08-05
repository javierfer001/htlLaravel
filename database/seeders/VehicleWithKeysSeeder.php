<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Key;

// sail artisan db:seed --class=VehicleWithKeysSeeder
class VehicleWithKeysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vehicle::factory(10)->has(
            Key::factory(random_int(1,3))
        )->create();
    }
}
