<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id', 'service_id', 'start_at', 'end_at', 'guest_count', 'status', 'total_price', 'comment'];
    protected function casts(): array { return ['start_at' => 'datetime', 'end_at' => 'datetime', 'total_price' => 'integer', 'guest_count' => 'integer']; }
    public function user() { return $this->belongsTo(User::class); }
    public function service() { return $this->belongsTo(Service::class); }
    public function extras() { return $this->belongsToMany(Extra::class, 'booking_extra')->withPivot('price_at_booking')->withTimestamps(); }
    public function review() { return $this->hasOne(Review::class); }
    public function canBeReviewed(): bool { return $this->status === 'confirmed' && $this->end_at->isPast() && ! $this->review()->exists(); }
}
