<?php

namespace App\Models;
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
        // Generate code with retry loop to avoid duplicate key under concurrency.
        // Format example: BK-260501-83F1A2
        do {
            $bookingCode = 'BK-' . now()->format('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(6)), 0, 6));
            $exists = DB::table('bookings')->where('booking_code', $bookingCode)->exists();
        } while ($exists);

        $booking->booking_code = $bookingCode;
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }
    public function bookingDetail()
    {
        return $this->hasOne(BookingDetail::class);
    }
}
