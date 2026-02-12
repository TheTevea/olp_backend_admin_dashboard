<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainBranch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'name_kh',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the branches under this main branch.
     */
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
