<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TDestination extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'name_kh',
        'code',
        'province_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the journeys originating from this destination.
     */
    public function journeysFrom()
    {
        return $this->hasMany(TJourney::class, 'from_destination_id');
    }

    /**
     * Get the journeys going to this destination.
     */
    public function journeysTo()
    {
        return $this->hasMany(TJourney::class, 'to_destination_id');
    }
}
