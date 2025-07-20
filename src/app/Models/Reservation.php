<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'user_id',
        'name_kanji',
        'name_kana_sei',
        'name_kana_mei',
        'phone_main',
        'phone_emergency',
        'email',
        'flight_number_arrival',
        'flight_number_departure',
        'flight_departure',
        'flight_return',
        'number_of_adults',
        'number_of_children',
        'notes',
        'start_datetime',
        'end_datetime',
        'options_json',
        'status',
        'total_price',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'options_json' => 'array',
    ];
    
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // App\Models\Reservation.php

    public function options()
    {
        return $this->belongsToMany(Option::class)
            ->withPivot('quantity', 'price', 'total_price')
            ->withTimestamps();
    }

    /**
     * 指定された期間で車両が利用可能かチェック
     */
    public static function isCarAvailable($carId, $startDateTime, $endDateTime, $excludeReservationId = null)
    {
        $query = self::where('car_id', $carId)
            ->where('status', '!=', 'cancelled') // キャンセルされた予約は除外
            ->where(function ($q) use ($startDateTime, $endDateTime) {
                // 期間が重複する予約を検索
                $q->where(function ($subQ) use ($startDateTime, $endDateTime) {
                    // 開始日時が指定期間内
                    $subQ->where('start_datetime', '>=', $startDateTime)
                         ->where('start_datetime', '<', $endDateTime);
                })->orWhere(function ($subQ) use ($startDateTime, $endDateTime) {
                    // 終了日時が指定期間内
                    $subQ->where('end_datetime', '>', $startDateTime)
                         ->where('end_datetime', '<=', $endDateTime);
                })->orWhere(function ($subQ) use ($startDateTime, $endDateTime) {
                    // 指定期間を完全に包含
                    $subQ->where('start_datetime', '<=', $startDateTime)
                         ->where('end_datetime', '>=', $endDateTime);
                });
            });

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return $query->count() === 0;
    }

    /**
     * 指定された期間で車両の利用可能時間を取得
     */
    public static function getCarAvailableTimes($carId, $date)
    {
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        // その日の予約を取得
        $reservations = self::where('car_id', $carId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($startOfDay, $endOfDay) {
                $q->whereBetween('start_datetime', [$startOfDay, $endOfDay])
                  ->orWhereBetween('end_datetime', [$startOfDay, $endOfDay])
                  ->orWhere(function ($subQ) use ($startOfDay, $endOfDay) {
                      $subQ->where('start_datetime', '<=', $startOfDay)
                           ->where('end_datetime', '>=', $endOfDay);
                  });
            })
            ->orderBy('start_datetime')
            ->get();

        $availableTimes = [];
        $currentTime = $startOfDay->copy();

        foreach ($reservations as $reservation) {
            if ($currentTime < $reservation->start_datetime) {
                $availableTimes[] = [
                    'start' => $currentTime->copy(),
                    'end' => $reservation->start_datetime->copy(),
                ];
            }
            $currentTime = max($currentTime, $reservation->end_datetime);
        }

        // 最後の予約後の時間も追加
        if ($currentTime < $endOfDay) {
            $availableTimes[] = [
                'start' => $currentTime->copy(),
                'end' => $endOfDay->copy(),
            ];
        }

        return $availableTimes;
    }
}
