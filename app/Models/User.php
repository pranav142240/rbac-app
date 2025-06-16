<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'primary_auth_method',
        'email_verified_at',
        'phone_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationships
     */
    public function authMethods()
    {
        return $this->hasMany(UserAuthMethod::class);
    }

    

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function magicLinks()
    {
        return $this->hasMany(MagicLink::class);
    }

    /**
     * Organizations this user belongs to
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }

    /**
     * Organization groups this user belongs to
     */
    public function organizationGroups()
    {
        return $this->belongsToMany(OrganizationGroup::class, 'organization_group_user')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }

    /**
     * Helper Methods
     */
    public function hasAuthMethod($type)
    {
        return $this->authMethods()->where('auth_method_type', $type)->exists();
    }

    public function getVerifiedAuthMethod($type)
    {
        return $this->authMethods()
            ->where('auth_method_type', $type)
            ->whereNotNull('auth_method_verified_at')
            ->first();
    }

    public function canUseAuthMethod($type)
    {
        $method = $this->getVerifiedAuthMethod($type);
        return $method && $method->is_active;
    }

    /**
     * Get user's primary authentication method
     */
    public function getPrimaryAuthMethod()
    {
        return $this->authMethods()
            ->where('auth_method_type', $this->primary_auth_method)
            ->first();
    }

    /**
     * Check if user has linked a specific social provider
     */
    public function hasLinkedProvider($provider)
    {
        return $this->socialAccounts()->where('provider', $provider)->exists();
    }

    /**
     * Get social account for a specific provider
     */
    public function getSocialAccount($provider)
    {
        return $this->socialAccounts()->where('provider', $provider)->first();
    }

    /**
     * Link a social account
     */
    public function linkSocialAccount($provider, $providerUser)
    {
        return SocialAccount::createOrUpdate($this, $provider, $providerUser);
    }

    /**
     * Get the user's initials for display
     */
    public function initials(): string
    {
        $name = $this->name;
        $words = explode(' ', $name);
        
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        
        return strtoupper(substr($name, 0, 2));
    }

    /**
     * Check if user belongs to a specific organization
     */
    public function belongsToOrganization($organization): bool
    {
        $organizationId = is_object($organization) ? $organization->id : $organization;
        return $this->organizations()->where('organization_id', $organizationId)->exists();
    }

    /**
     * Check if user belongs to a specific organization group
     */
    public function belongsToOrganizationGroup($group): bool
    {
        $groupId = is_object($group) ? $group->id : $group;
        return $this->organizationGroups()->where('organization_group_id', $groupId)->exists();
    }

    /**
     * Get groups for a specific organization
     */
    public function getGroupsForOrganization($organization)
    {
        $organizationId = is_object($organization) ? $organization->id : $organization;
        return $this->organizationGroups()
                    ->whereHas('organization', function($query) use ($organizationId) {
                        $query->where('id', $organizationId);
                    });
    }
}
