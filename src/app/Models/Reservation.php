<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
}
