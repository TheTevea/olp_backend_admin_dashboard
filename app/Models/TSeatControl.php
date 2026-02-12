<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TSeatControl extends Model
{
    protected $fillable = [
        'bus_schedule_id',
        'seat_number',
        'status',
        't_ticket_id',
        'locked_until',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
    ];

    /**
     * Get the bus schedule for this seat control.
     */
    public function busSchedule()
    {
        return $this->belongsTo(BusSchedule::class);
    }

    /**
     * Get the ticket associated with this seat.
     */
    public function tTicket()
    {
        return $this->belongsTo(TTicket::class);
    }

    /**
     * Check if the seat is available.
     */
    public function isAvailable()
    {
        if ($this->status === 'available') {
            return true;
        }

        // Check if lock has expired
        if ($this->status === 'locked' && $this->locked_until && Carbon::now()->isAfter($this->locked_until)) {
            $this->status = 'available';
            $this->locked_until = null;
            $this->save();
            return true;
        }

        return false;
    }

    /**
     * Lock the seat for a specified duration (minutes).
     */
    public function lock($minutes = 10)
    {
        $this->status = 'locked';
        $this->locked_until = Carbon::now()->addMinutes($minutes);
        $this->save();
    }

    /**
     * Book the seat with a ticket.
     */
    public function book($ticketId)
    {
        $this->status = 'booked';
        $this->t_ticket_id = $ticketId;
        $this->locked_until = null;
        $this->save();
    }

    /**
     * Release the seat.
     */
    public function release()
    {
        $this->status = 'available';
        $this->t_ticket_id = null;
        $this->locked_until = null;
        $this->save();
    }
}
