<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
    'user_id',
    'tour_id',
    'booking_date',
    'total_price',
    'status'
];
public function user() {
    return $this->belongsTo(User::class);
}

public function tour() {
    return $this->belongsTo(Tour::class);
}

public function payment() {
    return $this->hasOne(Payment::class);
}
}
