<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'type',
        'is_quantity',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_quantity' => 'boolean',
    ];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class)
            ->withPivot('quantity', 'price', 'total_price')
            ->withTimestamps();
    }
}
