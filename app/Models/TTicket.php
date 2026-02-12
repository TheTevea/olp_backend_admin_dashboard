<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTicket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'company_id',
        'branch_id',
        't_journey_id',
        'bus_schedule_id',
        'customer_id',
        't_agent_id',
        'seat_number',
        'departure_date',
        'departure_time',
        'price',
        'discount',
        'total_amount',
        'status',
        'payment_status',
        'created_by',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'departure_time' => 'datetime:H:i',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the company that issued this ticket.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the branch that issued this ticket.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the journey for this ticket.
     */
    public function tJourney()
    {
        return $this->belongsTo(TJourney::class);
    }

    /**
     * Get the bus schedule for this ticket.
     */
    public function busSchedule()
    {
        return $this->belongsTo(BusSchedule::class);
    }

    /**
     * Get the customer for this ticket.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the agent who sold this ticket.
     */
    public function tAgent()
    {
        return $this->belongsTo(TAgent::class);
    }

    /**
     * Get the user who created this ticket.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calculate total amount from price and discount.
     */
    public function calculateTotalAmount()
    {
        $this->total_amount = $this->price - $this->discount;
        return $this->total_amount;
    }
}
