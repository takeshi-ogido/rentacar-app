<?php

use Illuminate\Support\Carbon;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Car;
use App\Models\User;
use App\Models\Reservation;

echo "サンプル予約を追加中...\n";

$users = User::all();
if ($users->isEmpty()) {
    echo "ユーザーがいません。\n";
    exit(1);
}

$cars = Car::all();
$baseDate = Carbon::create(2025, 7, 1);

// ダミー名データ
$dummyNames = [
    ['sei' => '田中', 'mei' => '太郎', 'kana_sei' => 'タナカ', 'kana_mei' => 'タロウ'],
    ['sei' => '佐藤', 'mei' => '花子', 'kana_sei' => 'サトウ', 'kana_mei' => 'ハナコ'],
    ['sei' => '鈴木', 'mei' => '一郎', 'kana_sei' => 'スズキ', 'kana_mei' => 'イチロウ'],
    ['sei' => '高橋', 'mei' => '美咲', 'kana_sei' => 'タカハシ', 'kana_mei' => 'ミサキ'],
    ['sei' => '渡辺', 'mei' => '健太', 'kana_sei' => 'ワタナベ', 'kana_mei' => 'ケンタ'],
];

foreach ($cars as $car) {
    $date = $baseDate->copy();
    $numReservations = rand(2, 3);
    for ($i = 0; $i < $numReservations; $i++) {
        $user = $users->random();
        $period = rand(1, 3); // 1〜3日
        $start = $date->copy();
        $end = $date->copy()->addDays($period - 1);
        
        // ダミー名をランダム選択
        $dummyName = $dummyNames[array_rand($dummyNames)];
        
        // 料金計算（1日あたりの料金 × 日数）
        $dailyPrice = $car->price;
        $totalPrice = $dailyPrice * $period;
        
        Reservation::create([
            'car_id' => $car->id,
            'user_id' => $user->id,
            'start_datetime' => $start->copy()->setTime(10, 0),
            'end_datetime' => $end->copy()->setTime(18, 0),
            'status' => 'confirmed',
            'name_kanji' => $dummyName['sei'] . ' ' . $dummyName['mei'],
            'name_kana_sei' => $dummyName['kana_sei'],
            'name_kana_mei' => $dummyName['kana_mei'],
            'email' => 'sample' . rand(1, 999) . '@example.com',
            'phone_main' => '090-1234-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            'phone_emergency' => '080-9876-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            'number_of_adults' => rand(1, 4),
            'number_of_children' => rand(0, 2),
            'flight_number_arrival' => 'NH' . rand(100, 999),
            'flight_number_departure' => 'NH' . rand(100, 999),
            'flight_departure' => 'NRT',
            'flight_return' => 'CTS',
            'total_price' => $totalPrice,
            'notes' => 'サンプル予約です。',
            'note' => 'サンプル予約です。',
            'options_json' => '[]',
        ]);
        echo "車両{$car->id} {$start->format('Y-m-d')}〜{$end->format('Y-m-d')} ユーザー: {$user->name} 料金: ¥{$totalPrice}\n";
        $date = $end->copy()->addDay(); // 次の予約はこの予約の翌日以降
    }
}
echo "サンプル予約の追加が完了しました。\n"; 