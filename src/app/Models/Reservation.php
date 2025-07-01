<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

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
        'flight_departure',
        'flight_return',
        'note',
        'start_datetime',
        'end_datetime',
        'options_json',
        'status',
        'total_price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // 予約確認の処理（マイページ）
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];
    
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class)
            ->withPivot('quantity', 'price', 'total_price')
            ->withTimestamps();
    }

    /**
     * options_json をデコードして整形済みのオプション情報を返すアクセサ
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function formattedOptions(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->options_json) return [];

                $selectedOptions = json_decode($this->options_json, true);
                if (empty($selectedOptions)) return [];

                $options = Option::findMany(array_keys($selectedOptions))->keyBy('id');
                $displayDays = $this->start_datetime->copy()->startOfDay()->diffInDays($this->end_datetime->copy()->startOfDay()) + 1;

                $result = [];
                foreach ($selectedOptions as $id => $val) {
                    if (!isset($options[$id]) || !$val) continue;
                    $opt = $options[$id];
                    $qty = $opt->is_quantity ? (int)$val : 1;
                    $price = $opt->is_quantity ? ($opt->price * $qty) : ($opt->price * $qty * $displayDays);

                    $result[] = ['name' => $opt->name, 'price' => $price, 'unit_price' => $opt->price, 'quantity' => $qty, 'is_quantity' => $opt->is_quantity];
                }
                return $result;
            }
        );
    }
}
