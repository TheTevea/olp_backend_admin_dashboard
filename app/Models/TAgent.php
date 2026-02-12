<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TAgent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'phone',
        'email',
        'address',
        'commission_rate',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
    ];

    /**
     * Get the company that owns this agent.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the tickets sold by this agent.
     */
    public function tTickets()
    {
        return $this->hasMany(TTicket::class);
    }
}
