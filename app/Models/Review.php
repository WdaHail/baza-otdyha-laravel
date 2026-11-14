<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'booking_id', 'rating', 'body', 'status'];
    protected function casts(): array { return ['rating' => 'integer']; }
    public function user() { return $this->belongsTo(User::class); }
    public function booking() { return $this->belongsTo(Booking::class); }
    public function scopePublished($query) { return $query->where('status', 'published'); }
}
