<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'provider_email',
        'provider_name',
        'provider_avatar',
        'provider_data',
        'linked_at',
    ];

    protected $casts = [
        'provider_data' => 'array',
        'linked_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is a Google account
     */
    public function isGoogle()
    {
        return $this->provider === 'google';
    }

    /**
     * Get avatar URL with fallback
     */
    public function getAvatarUrl()
    {
        return $this->provider_avatar ?? '/images/default-avatar.png';
    }

    /**
     * Create or update social account
     */
    public static function createOrUpdate(User $user, $provider, $providerUser)
    {
        return static::updateOrCreate(
            [
                'user_id' => $user->id,
                'provider' => $provider,
            ],
            [
                'provider_id' => $providerUser->getId(),
                'provider_email' => $providerUser->getEmail(),
                'provider_name' => $providerUser->getName(),
                'provider_avatar' => $providerUser->getAvatar(),
                'provider_data' => [
                    'raw' => $providerUser->getRaw(),
                ],
                'linked_at' => now(),
            ]
        );
    }

    /**
     * Find user by provider credentials
     */
    public static function findByProvider($provider, $providerId)
    {
        return static::where('provider', $provider)
                    ->where('provider_id', $providerId)
                    ->first();
    }
}
