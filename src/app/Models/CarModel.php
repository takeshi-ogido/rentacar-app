<?php

namespace App\Models;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brand', 'type', 'price_per_day'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}


// CarModel（車種）
//    └── hasMany → Car（車両）
//           ├── hasMany → CarImage（画像）
//           └── hasMany → Reservation（予約）