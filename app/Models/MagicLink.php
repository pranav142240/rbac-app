<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class MagicLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'token',
        'ip_address',
        'user_agent',
        'expires_at',
        'used_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
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
            ->whereNull('used_at');
    }

    public function scopeByToken($query, $token)
    {
        return $query->where('token', $token);
    }

    /**
     * Helper methods
     */
    public function isValid()
    {
        return $this->expires_at > now() && is_null($this->used_at);
    }

    public function isExpired()
    {
        return $this->expires_at <= now();
    }

    public function isUsed()
    {
        return !is_null($this->used_at);
    }

    public function markAsUsed()
    {
        $this->update(['used_at' => now()]);
    }

    /**
     * Create a new magic link
     */
    public static function createForUser($user, $email, $ipAddress = null, $userAgent = null)
    {
        // Invalidate any existing magic links for this user and email
        static::where('user_id', $user->id)
              ->where('email', $email)
              ->whereNull('used_at')
              ->update(['used_at' => now()]);

        // Generate a secure token
        $token = static::generateToken();

        return static::create([
            'user_id' => $user->id,
            'email' => $email,
            'token' => $token,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'expires_at' => now()->addMinutes(15), // 15 minutes expiry
            'attempts' => 0,
        ]);
    }

    /**
     * Verify a magic link token
     */
    public static function verifyToken($token, $ipAddress = null, $userAgent = null)
    {
        $magicLink = static::where('token', $token)
                          ->whereNull('used_at')
                          ->where('expires_at', '>', now())
                          ->first();

        if (!$magicLink) {
            return null;
        }

        // Optional: Add IP/User Agent validation for extra security
        // if ($ipAddress && $magicLink->ip_address && $magicLink->ip_address !== $ipAddress) {
        //     return null;
        // }

        // Mark as used
        $magicLink->markAsUsed();

        return $magicLink;
    }

    /**
     * Generate a secure token
     */
    public static function generateToken()
    {
        return bin2hex(random_bytes(32)); // 64 character hex string
    }
}
