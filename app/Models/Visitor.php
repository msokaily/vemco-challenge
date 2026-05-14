<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'sensor_id',
        'date',
        'count',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'count' => 'integer',
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
