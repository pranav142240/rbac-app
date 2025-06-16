<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAuthMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'auth_method_type',
        'identifier',
        'is_active',
        'auth_method_verified_at',
        'provider_id',
        'provider_data',
    ];

    protected $casts = [
        'auth_method_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'provider_data' => 'array',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('auth_method_verified_at');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('auth_method_type', $type);
    }

    /**
     * Helper methods
     */
    public function isVerified()
    {
        return !is_null($this->auth_method_verified_at);
    }

    public function markAsVerified()
    {
        $this->update([
            'auth_method_verified_at' => now(),
            'is_active' => true,
        ]);
    }
}
