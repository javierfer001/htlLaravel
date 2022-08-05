<?php

namespace Database\Factories;

use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TechnicianFactory extends Factory
{
    protected $model = Technician::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name'   => $this->faker->firstName,
            'last_name'    => $this->faker->lastName,
            'truck_number' => $this->faker->numberBetween($min = 1000, $max = 9999)
        ];
    }
}
