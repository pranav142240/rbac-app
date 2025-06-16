<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Organization;
use App\Models\OrganizationGroup;
use App\Models\User;

echo "=== RBAC App Database Status ===\n\n";

// Check Organizations
$organizations = Organization::with(['organizationGroups', 'users'])->get();
echo "Organizations (" . $organizations->count() . "):\n";
foreach ($organizations as $org) {
    echo "- {$org->name} ({$org->slug})\n";
    echo "  Groups: " . $org->organizationGroups->count() . "\n";
    echo "  Members: " . $org->users->count() . "\n";
    echo "  Website: " . ($org->website ?? 'N/A') . "\n\n";
}

// Check Walkwel Technology Groups
$walkwelTech = Organization::where('slug', 'walkwel-technology')->first();
if ($walkwelTech) {
    echo "=== Walkwel Technology Groups ===\n";
    $groups = $walkwelTech->organizationGroups()->withCount('users')->get();
    foreach ($groups as $group) {
        echo "- {$group->name} ({$group->slug})\n";
        echo "  Description: {$group->description}\n";
        echo "  Members: {$group->users_count}\n";
        echo "  Status: " . ($group->is_active ? 'Active' : 'Inactive') . "\n\n";
    }
}

// Check Users
$users = User::with(['organizations', 'organizationGroups'])->get();
echo "=== Users (" . $users->count() . ") ===\n";
foreach ($users as $user) {
    echo "- {$user->name} ({$user->email})\n";
    echo "  Organizations: " . $user->organizations->count() . "\n";
    echo "  Groups: " . $user->organizationGroups->count() . "\n";
    if ($user->organizationGroups->count() > 0) {
        echo "  Group memberships: " . $user->organizationGroups->pluck('name')->join(', ') . "\n";
    }
    echo "\n";
}

echo "=== Summary ===\n";
echo "Total Organizations: " . Organization::count() . "\n";
echo "Total Groups: " . OrganizationGroup::count() . "\n";
echo "Total Users: " . User::count() . "\n";
echo "Walkwel Technology Groups: " . ($walkwelTech ? $walkwelTech->organizationGroups->count() : 0) . "\n";
