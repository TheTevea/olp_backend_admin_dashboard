<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bus extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bus_type_id',
        'company_id',
        'branch_id',
        'plate_number',
        'code',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    /**
     * Get the bus type.
     */
    public function busType()
    {
        return $this->belongsTo(BusType::class);
    }

    /**
     * Get the company that owns the bus.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the branch that operates the bus.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the schedules for this bus.
     */
    public function busSchedules()
    {
        return $this->hasMany(BusSchedule::class);
    }
}
