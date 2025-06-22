<?php

namespace Database\Factories;

use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarModelFactory extends Factory
{
    protected $model = CarModel::class;

    public function definition()
    {
        return [
            'car_model' => $this->faker->unique()->word(), // 車種名を適当に作る例
        ];
    }
}
