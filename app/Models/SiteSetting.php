<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['key', 'value'];
}
