<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Organization;
use App\Models\OrganizationGroup;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Admin Organization Assignment Issue ===\n\n";

// Find or create a test user
$user = User::where('email', 'test.admin@example.com')->first();
if (!$user) {
    $user = User::create([
        'name' => 'Test Admin User',
        'email' => 'test.admin@example.com',
        'password' => bcrypt('password123'),
        'primary_auth_method' => 'email_password',
        'email_verified_at' => now(),
    ]);
    echo "Created test user: {$user->name}\n";
} else {
    echo "Using existing test user: {$user->name}\n";
}

// Clear all memberships first
echo "\n1. Clearing all memberships...\n";
$user->organizations()->sync([]);
$user->organizationGroups()->sync([]);
$user->refresh();

echo "Organizations: " . ($user->organizations->count() > 0 ? $user->organizations->pluck('name')->join(', ') : 'None') . "\n";
echo "Groups: " . ($user->organizationGroups->count() > 0 ? $user->organizationGroups->pluck('name')->join(', ') : 'None') . "\n";

// Get organizations
$walkwelTech = Organization::where('name', 'Walkwel Technology')->first();
$innovationCorp = Organization::where('name', 'Innovation Corp')->first();

if (!$walkwelTech || !$innovationCorp) {
    echo "ERROR: Required organizations not found!\n";
    exit(1);
}

echo "\n2. Testing admin assignment - assigning ONLY organization (no groups)...\n";

// Simulate what admin does: assign organization only (no groups selected)
$validated = [
    'organizations' => [$walkwelTech->id],
    'organization_groups' => [] // No groups selected
];

echo "Simulating admin form submission:\n";
echo "- Organizations to assign: [" . $walkwelTech->id . "] (" . $walkwelTech->name . ")\n";
echo "- Groups to assign: [] (none selected)\n\n";

// Execute the same logic as the controller
if (isset($validated['organizations'])) {
    $organizations = Organization::whereIn('id', $validated['organizations'])->get();
    $user->organizations()->sync($organizations);
    echo "Step 1: Synced organizations - assigned " . $organizations->pluck('name')->join(', ') . "\n";
} else {
    $user->organizations()->sync([]);
    echo "Step 1: Cleared all organizations\n";
}

// Refresh user to get updated organizations
$user->refresh();
$userOrgIds = $user->organizations->pluck('id')->toArray();
echo "Step 2: User now belongs to organizations with IDs: " . implode(', ', $userOrgIds) . "\n";

// Update organization groups
if (isset($validated['organization_groups']) && !empty($userOrgIds)) {
    $validGroups = OrganizationGroup::whereIn('id', $validated['organization_groups'])
                                   ->whereIn('organization_id', $userOrgIds)
                                   ->get();
    $user->organizationGroups()->sync($validGroups);
    echo "Step 3: Synced groups - assigned " . $validGroups->pluck('name')->join(', ') . "\n";
} else {
    $user->organizationGroups()->sync([]);
    echo "Step 3: Cleared all groups (none selected or no valid orgs)\n";
}

$user->refresh();

echo "\nFINAL RESULT:\n";
echo "Organizations: " . ($user->organizations->count() > 0 ? $user->organizations->pluck('name')->join(', ') : 'None') . "\n";
echo "Groups: " . ($user->organizationGroups->count() > 0 ? $user->organizationGroups->pluck('name')->join(', ') : 'None') . "\n";

if ($user->organizationGroups->count() > 0) {
    echo "\n❌ PROBLEM: User was assigned to groups even though none were selected!\n";
    echo "Groups assigned:\n";
    foreach ($user->organizationGroups as $group) {
        echo "- {$group->name} (from {$group->organization->name})\n";
    }
} else {
    echo "\n✅ CORRECT: User is only in organization, no groups assigned\n";
}

echo "\n=== Checking for automatic assignment in model events ===\n";

// Check if there are any model events that might be causing this
$user->organizations()->sync([]);
$user->organizationGroups()->sync([]);
$user->refresh();

echo "Before organization assignment:\n";
echo "Organizations: " . ($user->organizations->count() > 0 ? $user->organizations->pluck('name')->join(', ') : 'None') . "\n";
echo "Groups: " . ($user->organizationGroups->count() > 0 ? $user->organizationGroups->pluck('name')->join(', ') : 'None') . "\n";

// Just add to organization using the model's addMember method
echo "\nUsing Organization->addMember() method:\n";
$walkwelTech->addMember($user);
$user->refresh();

echo "After addMember():\n";
echo "Organizations: " . ($user->organizations->count() > 0 ? $user->organizations->pluck('name')->join(', ') : 'None') . "\n";
echo "Groups: " . ($user->organizationGroups->count() > 0 ? $user->organizationGroups->pluck('name')->join(', ') : 'None') . "\n";

echo "\nTest completed.\n";

?>
