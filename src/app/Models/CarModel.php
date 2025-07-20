<?php

namespace App\Models;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = ['car_model', 'brand', 'type', 'price_per_day'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    // name属性のアクセサ（car_modelカラムをnameとしてアクセス可能にする）
    public function getNameAttribute()
    {
        return $this->car_model;
    }

    // name属性のミューテータ（nameをcar_modelとして保存する）
    public function setNameAttribute($value)
    {
        $this->attributes['car_model'] = $value;
    }
}


// CarModel（車種）
//    └── hasMany → Car（車両）
//           ├── hasMany → CarImage（画像）
//           └── hasMany → Reservation（予約）