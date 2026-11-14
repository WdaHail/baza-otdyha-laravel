<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    protected $fillable = ['name', 'description', 'price', 'icon', 'is_active'];
    protected function casts(): array { return ['price' => 'integer', 'is_active' => 'boolean']; }
    public function bookings() { return $this->belongsToMany(Booking::class, 'booking_extra')->withPivot('price_at_booking')->withTimestamps(); }
}
