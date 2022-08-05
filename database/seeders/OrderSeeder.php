<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

// sail artisan db:seed --class=OrderSeeder
class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory(10)->create();
    }
}
