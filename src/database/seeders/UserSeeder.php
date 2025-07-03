<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 複数のダミーユーザーを作成
        User::factory()->count(10)->create();

        // 固定データ（検索テスト用）
        User::create([
            'name' => '山田 太郎',
            'email' => 'taro@example.com',
            'phone_number' => '09012345678',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '佐藤 花子',
            'email' => 'hanako@example.com',
            'phone_number' => '08098765432',
            'password' => Hash::make('password'),
        ]);
    }
}