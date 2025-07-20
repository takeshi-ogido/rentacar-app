<?php

use Illuminate\Support\Carbon;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Boot Laravel
$kernel->bootstrap();

use App\Models\Car;
use App\Models\Reservation;

echo "全車両の重複期間予約を1件だけ残して削除中...\n";

$cars = Car::all();
foreach ($cars as $car) {
    $reservations = $car->reservations()->orderBy('start_datetime')->get();
    $toDelete = [];
    $count = count($reservations);
    for ($i = 0; $i < $count; $i++) {
        for ($j = $i + 1; $j < $count; $j++) {
            $res1 = $reservations[$i];
            $res2 = $reservations[$j];
            $start1 = $res1->start_datetime;
            $end1 = $res1->end_datetime;
            $start2 = $res2->start_datetime;
            $end2 = $res2->end_datetime;
            // 期間が重複していたら後ろの予約を削除対象に
            if ($start1 < $end2 && $start2 < $end1) {
                $toDelete[$res2->id] = $res2;
            }
        }
    }
    foreach ($toDelete as $id => $reservation) {
        echo "車両{$car->id} 予約{$id} ({$reservation->start_datetime->format('Y-m-d')}〜{$reservation->end_datetime->format('Y-m-d')}) を削除\n";
        $reservation->delete();
    }
}
echo "重複期間予約の削除が完了しました。\n"; 