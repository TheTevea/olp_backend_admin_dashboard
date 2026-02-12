<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bus_id',
        't_journey_id',
        'departure_time',
        'arrival_time',
        'schedule_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'schedule_date' => 'date',
        'departure_time' => 'datetime:H:i',
        'arrival_time' => 'datetime:H:i',
    ];

    /**
     * Get the bus for this schedule.
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Get the journey for this schedule.
     */
    public function tJourney()
    {
        return $this->belongsTo(TJourney::class);
    }

    /**
     * Get the tickets for this schedule.
     */
    public function tTickets()
    {
        return $this->hasMany(TTicket::class);
    }

    /**
     * Get the seat controls for this schedule.
     */
    public function tSeatControls()
    {
        return $this->hasMany(TSeatControl::class);
    }
}
