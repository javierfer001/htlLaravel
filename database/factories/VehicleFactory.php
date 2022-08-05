<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use \Faker\Provider\Fakecar;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->faker->addProvider(new Fakecar($this->faker));
        $vehicle = $this->faker->vehicleArray();

        return [
            'year'  => $this->faker->biasedNumberBetween(2005, 2022, 'sqrt'),
            'make'  => $vehicle['brand'],
            'model' => $vehicle['model'],
            'vin'   => $this->faker->unique()->vin
        ];
    }
}
