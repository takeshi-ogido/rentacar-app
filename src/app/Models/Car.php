<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\CarImage;
use App\Models\CarModel;
use App\Models\Reservation;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'capacity',
        'price',
        'transmission',
        'smoking_preference',
        'has_bluetooth',
        'has_back_monitor',
        'has_navigation',
        'has_etc',
        'description',
        'is_public',
        'car_model_id',
        'car_number',       // 必要であれば保持
        'color',            // 必要であれば保持
        'car_vin',          // 必要であれば保持
        'passenger',        // capacityと重複の可能性あり
        'store_id',         // 店舗がある場合に使用
    ];

    /**
     * モデルとのリレーション（belongsTo）
     */
    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    /**
     * 車両画像とのリレーション（hasMany）
     */
    public function images()
    {
        return $this->hasMany(CarImage::class, 'car_id');
    }

    /**
     * 予約とのリレーション（hasMany）
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'car_id');
    }

    /**
     * 利用可能な期間に空いている車両だけ取得するスコープ
     */
    public function scopeAvailableBetween($query, $start, $end)
    {
        return $query->whereDoesntHave('reservations', function ($q) use ($start, $end) {
            $q->where(function ($q2) use ($start, $end) {
                $q2->where('start_datetime', '<', $end)
                   ->where('end_datetime', '>', $start);
            });
        });
    }

    /**
     * 禁煙・喫煙のラベル取得アクセサ
     */
    protected function smokingPreferenceLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->smoking_preference === 'non-smoking' ? '🚭 禁煙' : '🚬 喫煙可',
        );
    }

    /**
     * 装備リストを配列で取得するアクセサ
     */
    protected function equipmentList(): Attribute
    {
        return Attribute::make(
            get: function () {
                $list = [];

                if ($this->has_bluetooth) {
                    $list[] = ['icon' => '🎵', 'label' => 'Bluetooth'];
                }
                if ($this->has_back_monitor) {
                    $list[] = ['icon' => '📹', 'label' => 'バックモニター'];
                }
                if ($this->has_navigation) {
                    $list[] = ['icon' => '🗺', 'label' => 'ナビ付き'];
                }
                if ($this->has_etc) {
                    $list[] = ['icon' => '💳', 'label' => 'ETC車載器搭載'];
                }

                return $list;
            }
        );
    }
}




