<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Organization;
use App\Models\OrganizationGroup;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Checking Current State of Pranav User ===\n\n";

$user = User::where('email', 'pranav@user.com')->first();
if (!$user) {
    echo "User pranav@user.com not found!\n";
    exit(1);
}

echo "User: {$user->name} ({$user->email})\n\n";

echo "CURRENT ASSIGNMENTS:\n";
echo "Organizations ({$user->organizations->count()}):\n";
foreach ($user->organizations as $org) {
    echo "- {$org->name} (ID: {$org->id})\n";
}

echo "\nGroups ({$user->organizationGroups->count()}):\n";
foreach ($user->organizationGroups as $group) {
    echo "- {$group->name} (ID: {$group->id}) - from {$group->organization->name}\n";
}

// Check available groups for user's organizations
echo "\nAVAILABLE GROUPS IN USER'S ORGANIZATIONS:\n";
foreach ($user->organizations as $org) {
    echo "\n{$org->name} groups:\n";
    foreach ($org->organizationGroups as $group) {
        $isMember = $user->belongsToOrganizationGroup($group);
        echo "- {$group->name} (ID: {$group->id}) " . ($isMember ? "✅ MEMBER" : "❌ NOT MEMBER") . "\n";
    }
}

// Now let's test removing a specific group
echo "\n=== TESTING: Remove user from ONE group manually ===\n";

$firstGroup = $user->organizationGroups->first();
if ($firstGroup) {
    echo "Removing user from: {$firstGroup->name}\n";
    
    // Get current group IDs
    $currentGroupIds = $user->organizationGroups->pluck('id')->toArray();
    echo "Current group IDs: " . implode(', ', $currentGroupIds) . "\n";
    
    // Remove the first group
    $newGroupIds = array_filter($currentGroupIds, function($id) use ($firstGroup) {
        return $id !== $firstGroup->id;
    });
    echo "New group IDs after removal: " . implode(', ', $newGroupIds) . "\n";
    
    // Sync the new groups
    $user->organizationGroups()->sync($newGroupIds);
    $user->refresh();
    
    echo "\nAfter removal:\n";
    echo "Groups ({$user->organizationGroups->count()}):\n";
    foreach ($user->organizationGroups as $group) {
        echo "- {$group->name} (ID: {$group->id})\n";
    }
}

echo "\n=== TESTING: What happens when we assign organization again ===\n";

// Get the organization IDs
$orgIds = $user->organizations->pluck('id')->toArray();
echo "Re-syncing organizations with IDs: " . implode(', ', $orgIds) . "\n";

// Clear groups first
echo "Clearing all groups...\n";
$user->organizationGroups()->sync([]);
$user->refresh();

echo "Groups after clearing: " . ($user->organizationGroups->count() > 0 ? $user->organizationGroups->pluck('name')->join(', ') : 'None') . "\n";

// Re-sync organizations (this should not add any groups)
$user->organizations()->sync($orgIds);
$user->refresh();

echo "After re-syncing organizations:\n";
echo "Organizations: " . $user->organizations->pluck('name')->join(', ') . "\n";
echo "Groups: " . ($user->organizationGroups->count() > 0 ? $user->organizationGroups->pluck('name')->join(', ') : 'None') . "\n";

if ($user->organizationGroups->count() > 0) {
    echo "\n❌ PROBLEM FOUND: Groups were automatically added when syncing organizations!\n";
    echo "This suggests there might be a model event or observer causing this.\n";
} else {
    echo "\n✅ No automatic group assignment when syncing organizations.\n";
}

?>
