<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'user_id',
        'tour_id',
        'booking_date',
        'total_price',
        'status'
    ];


    protected static function boot()
{
    parent::boot();

    static::creating(function ($booking) {

        if ($booking->booking_code) return;

        $date = now()->format('ymd'); 

        $count = DB::table('bookings')
            ->whereDate('created_at', now())
            ->count() + 1;

        $booking->booking_code =
            'BK-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    });
}
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function bookingDetail()
    {
        return $this->hasOne(BookingDetail::class);
    }
}
