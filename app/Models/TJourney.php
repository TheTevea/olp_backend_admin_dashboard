<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TJourney extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'from_destination_id',
        'to_destination_id',
        'code',
        'distance_km',
        'duration_hours',
        'base_price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'distance_km' => 'decimal:2',
        'duration_hours' => 'decimal:2',
        'base_price' => 'decimal:2',
    ];

    /**
     * Get the company that owns this journey.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the origin destination.
     */
    public function fromDestination()
    {
        return $this->belongsTo(TDestination::class, 'from_destination_id');
    }

    /**
     * Get the destination.
     */
    public function toDestination()
    {
        return $this->belongsTo(TDestination::class, 'to_destination_id');
    }

    /**
     * Get the bus schedules for this journey.
     */
    public function busSchedules()
    {
        return $this->hasMany(BusSchedule::class);
    }

    /**
     * Get the tickets for this journey.
     */
    public function tTickets()
    {
        return $this->hasMany(TTicket::class);
    }
}
