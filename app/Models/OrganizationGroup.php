<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OrganizationGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'description',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            if (empty($group->slug)) {
                $group->slug = Str::slug($group->name);
                
                // Ensure unique slug within organization
                $originalSlug = $group->slug;
                $counter = 1;
                while (static::where('organization_id', $group->organization_id)
                           ->where('slug', $group->slug)
                           ->exists()) {
                    $group->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });

        static::updating(function ($group) {
            if ($group->isDirty('name') && empty($group->slug)) {
                $group->slug = Str::slug($group->name);
                
                // Ensure unique slug within organization
                $originalSlug = $group->slug;
                $counter = 1;
                while (static::where('organization_id', $group->organization_id)
                           ->where('slug', $group->slug)
                           ->where('id', '!=', $group->id)
                           ->exists()) {
                    $group->slug = $originalSlug . '-' . $counter;
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
     * The organization this group belongs to
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Users that belong to this group
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_group_user')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }

    /**
     * Scope for active groups
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for groups within a specific organization
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Check if user is member of this group
     */
    public function hasMember(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Add user to group
     */
    public function addMember(User $user): void
    {
        // First ensure user is member of the organization
        if (!$this->organization->hasMember($user)) {
            $this->organization->addMember($user);
        }

        // Then add to group if not already a member
        if (!$this->hasMember($user)) {
            $this->users()->attach($user->id, ['joined_at' => now()]);
        }
    }

    /**
     * Remove user from group
     */
    public function removeMember(User $user): void
    {
        $this->users()->detach($user->id);
    }

    /**
     * Get the full name including organization
     */
    public function getFullNameAttribute(): string
    {
        return $this->organization->name . ' - ' . $this->name;
    }
}
