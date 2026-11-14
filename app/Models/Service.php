<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'slug', 'category', 'short_description', 'description', 'price_per_hour', 'capacity', 'cover_url', 'icon', 'is_active', 'sort_order'];
    protected function casts(): array { return ['price_per_hour' => 'integer', 'capacity' => 'integer', 'is_active' => 'boolean']; }
    public function bookings() { return $this->hasMany(Booking::class); }
    public function scopeActive($query) { return $query->where('is_active', true); }
}
