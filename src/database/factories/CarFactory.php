<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        return [
            'name' => 'ダミーカー ' . $this->faker->unique()->word(),
            'type' => $this->faker->randomElement(['セダン', 'SUV', 'ミニバン']),
            'capacity' => $this->faker->numberBetween(2, 8),
            'price' => $this->faker->numberBetween(5000, 15000),
            'smoking_preference' => $this->faker->randomElement(['smoking', 'non-smoking']),
            'transmission' => $this->faker->randomElement(['AT', 'MT']),
            'has_bluetooth' => $this->faker->boolean,
            'has_back_monitor' => $this->faker->boolean,
            'has_navigation' => $this->faker->boolean,
            'has_etc' => $this->faker->boolean,
            'car_model_id' => CarModel::factory(), // CarModelとのリレーション
        ];
    }
}
