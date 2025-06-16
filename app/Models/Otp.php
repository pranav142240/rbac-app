<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'identifier',
        'otp_code',
        'type',
        'expires_at',
        'verified_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'attempts' => 'integer',
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
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now())
            ->whereNull('verified_at')
            ->where('attempts', '<', 5);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Helper methods
     */
    public function isValid()
    {
        return $this->expires_at > now() && 
               is_null($this->verified_at) && 
               $this->attempts < 5;
    }

    public function isExpired()
    {
        return $this->expires_at <= now();
    }

    public function incrementAttempts()
    {
        $this->increment('attempts');
    }

    public function markAsVerified()
    {
        $this->update(['verified_at' => now()]);
    }

    /**
     * Generate OTP code
     */
    public static function generateCode($length = 6)
    {
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}
