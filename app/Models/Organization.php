<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'settings',
        'is_active',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($organization) {
            if (empty($organization->slug)) {
                $organization->slug = Str::slug($organization->name);
                
                // Ensure unique slug
                $originalSlug = $organization->slug;
                $counter = 1;
                while (static::where('slug', $organization->slug)->exists()) {
                    $organization->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });

        static::updating(function ($organization) {
            if ($organization->isDirty('name') && empty($organization->slug)) {
                $organization->slug = Str::slug($organization->name);
                
                // Ensure unique slug
                $originalSlug = $organization->slug;
                $counter = 1;
                while (static::where('slug', $organization->slug)->where('id', '!=', $organization->id)->exists()) {
                    $organization->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Users that belong to this organization
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }

    /**
     * Groups within this organization
     */
    public function organizationGroups(): HasMany
    {
        return $this->hasMany(OrganizationGroup::class);
    }

    /**
     * Active groups within this organization
     */
    public function activeGroups(): HasMany
    {
        return $this->hasMany(OrganizationGroup::class)->where('is_active', true);
    }

    /**
     * Scope for active organizations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the full address
     */
    public function getFullAddressAttribute(): string
    {
        $addressParts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $addressParts);
    }

    /**
     * Check if user is member of this organization
     */
    public function hasMember(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Add user to organization
     */
    public function addMember(User $user): void
    {
        if (!$this->hasMember($user)) {
            $this->users()->attach($user->id, ['joined_at' => now()]);
        }
    }

    /**
     * Remove user from organization
     */
    public function removeMember(User $user): void
    {
        $this->users()->detach($user->id);
        
        // Also remove from all groups in this organization
        foreach ($this->organizationGroups as $group) {
            $group->removeMember($user);
        }
    }
}
