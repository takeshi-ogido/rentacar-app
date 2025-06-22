<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $cars = Car::all();
        $users = User::all();

        if ($cars->isEmpty() || $users->isEmpty()) {
            $this->command->info('No cars or users found, skipping reservation seeding.');
            return;
        }

        // 例1: 特定の車に明確な予約を入れる (テスト用)
        $testCar = $cars->first();
        if ($testCar) {
            Reservation::factory()->create([
                'car_id' => $testCar->id,
                'user_id' => $users->random()->id,
                'start_datetime' => Carbon::now()->addDays(3)->setTime(10, 0, 0), // 3日後の10時
                'end_datetime' => Carbon::now()->addDays(5)->setTime(10, 0, 0),   // 5日後の10時
                'status' => 'confirmed',
            ]);

            Reservation::factory()->create([
                'car_id' => $testCar->id,
                'user_id' => $users->random()->id,
                'start_datetime' => Carbon::now()->addDays(10)->setTime(14, 0, 0), // 10日後の14時
                'end_datetime' => Carbon::now()->addDays(11)->setTime(11, 0, 0),  // 11日後の11時
                'status' => 'confirmed',
            ]);
        }

        // 例2: 他のいくつかの車にもランダムな予約を数件ずつ入れる
        foreach ($cars->skip(1)->take(10) as $car) { // 最初の1台を除いた10台に
            Reservation::factory(rand(0, 4))->create([ // 0〜4件の予約
                'car_id' => $car->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}