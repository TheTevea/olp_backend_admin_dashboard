<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'total_seats',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_seats' => 'integer',
    ];

    /**
     * Get the buses of this type.
     */
    public function buses()
    {
        return $this->hasMany(Bus::class);
    }
}
