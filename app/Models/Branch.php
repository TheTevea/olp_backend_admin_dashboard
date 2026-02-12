<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'main_branch_id',
        'name',
        'name_kh',
        'code',
        'address',
        'address_kh',
        'phone',
        'email',
        'is_active',
        'is_headquarters',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_headquarters' => 'boolean',
    ];

    /**
     * Get the company that owns the branch.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the main branch that this branch belongs to.
     */
    public function mainBranch()
    {
        return $this->belongsTo(MainBranch::class);
    }

    /**
     * Get the users in this branch.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
