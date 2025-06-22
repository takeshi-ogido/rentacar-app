<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ユーザーを10人作成
        User::factory(10)->create();

        // テストユーザーを1人作成
        User::factory()->create([
            'name' => 'Test User',
        'email' => 'test@example.com', // 固定のメールアドレスの方がテストしやすい
        // 'phone_number' => '0123456789', // UserFactoryで定義されていれば不要
            'password' => Hash::make('password'),
        ]);

        // CarModelSeeder があれば先に実行
        // $this->call(CarModelSeeder::class);
        $this->call([
            CarSeeder::class,      // 車両データ
            OptionSeeder::class,   // オプションデータ (ReservationFactoryが依存する場合など)
            ReservationSeeder::class, // 予約データ
        ]);
    }
}
