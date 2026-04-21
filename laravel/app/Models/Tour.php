<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    // 
    protected $fillable = [
    'title',
    'description',
    'price',
    'duration',
    'location',
    'start_date',
    'end_date',
    'slots',
    'available_slots',
    'image',
    'status'
];
public function bookings() {
    return $this->hasMany(Booking::class);
}
}
