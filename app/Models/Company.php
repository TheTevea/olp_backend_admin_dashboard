<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'name_kh',
        'address',
        'address_kh',
        'phone',
        'email',
        'website',
        'logo',
        'vat_tin',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the branches for this company.
     */
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get the users for this company.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
