<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Technician;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{

    protected $model = Order::class;
    private $first;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $vehicle = Vehicle::inRandomOrder()->first();
        $key = $vehicle->keys()->inRandomOrder()->first();
        $technician = Technician::inRandomOrder()->first();

        // Make sure the key and technician exist
        if (!$key || !$technician) {
            exit();
        }

        return [
            'vehicle_id'    => $vehicle->id,
            'key_id'        => $key->id,
            'technician_id' => $technician->id,
            'status'        => $this->faker->randomElement(Order::STATUSES),
            'note'          => $this->faker->sentence
        ];
    }
}
