<?php

namespace Database\Factories;

use App\Models\Key;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Key>
 */
class KeyFactory extends Factory
{
    protected $model = Key::class;
    private $arr;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->arr = [
            'name'        => $this->faker->unique()->company,
            'description' => $this->faker->sentence,
            'price'       =>
                $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100)
        ];
        return $this->arr;
    }
}
