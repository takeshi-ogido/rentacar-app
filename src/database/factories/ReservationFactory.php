<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Car;

class ReservationFactory extends Factory
{
    protected $model = \App\Models\Reservation::class;

    public function definition()
    {
        $faker_ja = \Faker\Factory::create('ja_JP');
        $start_datetime = Carbon::instance($this->faker->dateTimeBetween('+1 day', '+2 weeks'))
                                ->setHour($this->faker->numberBetween(9, 17))->setMinute(0)->setSecond(0);
        $end_datetime = (clone $start_datetime)->addDays($this->faker->numberBetween(1, 5))
                                            ->addHours($this->faker->numberBetween(1, 5));

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'car_id' => Car::inRandomOrder()->first()?->id ?? Car::factory(),
            'start_datetime' => $start_datetime,
            'end_datetime' => $end_datetime,
            'name_kanji' => $faker_ja->name(),
            'name_kana_sei' => $faker_ja->lastKanaName(),
            'name_kana_mei' => $faker_ja->firstKanaName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_main' => $faker_ja->phoneNumber(),
            'total_price' => $this->faker->numberBetween(5000, 50000),
            'options_json' => json_encode([]), // 必要に応じてオプションもダミーで
            'status' => $this->faker->randomElement(['confirmed', 'pending', 'cancelled']), // 様々なステータス
            'flight_departure' => $this->faker->optional()->bothify('??###'),
            'flight_return' => $this->faker->optional()->bothify('??###'),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}