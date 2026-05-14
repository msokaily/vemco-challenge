<?php

namespace App\Models;

use App\Enums\SensorStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'location_id',
    ];

    protected $casts = [
        'status' => SensorStatus::class,
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }
}
