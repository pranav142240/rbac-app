<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Organization;
use App\Models\OrganizationGroup;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Dashboard Group Display Issue ===\n\n";

// Find the test user
$user = User::where('email', 'pranav@user.com')->first();
if (!$user) {
    echo "Test user 'pranav@user.com' not found!\n";
    exit(1);
}

echo "Testing with user: {$user->name} ({$user->email})\n\n";

// Check user's organizations
echo "=== USER'S ORGANIZATIONS ===\n";
$userOrganizations = $user->organizations;
foreach ($userOrganizations as $org) {
    echo "- {$org->name} (ID: {$org->id})\n";
}

if ($userOrganizations->count() == 0) {
    echo "User has no organizations\n";
}

echo "\n=== USER'S ASSIGNED GROUPS ===\n";
$userGroups = $user->organizationGroups;
foreach ($userGroups as $group) {
    echo "- {$group->name} (Organization: {$group->organization->name}) (ID: {$group->id})\n";
}

if ($userGroups->count() == 0) {
    echo "User has no assigned groups\n";
}

echo "\n=== ALL GROUPS IN USER'S ORGANIZATIONS ===\n";
foreach ($userOrganizations as $org) {
    echo "Organization: {$org->name}\n";
    $allGroupsInOrg = $org->organizationGroups;
    foreach ($allGroupsInOrg as $group) {
        $isUserMember = $user->organizationGroups->contains('id', $group->id);
        echo "  - {$group->name} " . ($isUserMember ? "[USER IS MEMBER]" : "[USER NOT MEMBER]") . "\n";
    }
    echo "\n";
}

echo "=== DASHBOARD DATA VERIFICATION ===\n";
echo "What dashboard shows for 'My Groups':\n";
echo "Count: " . $user->organizationGroups->count() . "\n";
foreach ($user->organizationGroups->take(3) as $group) {
    echo "- {$group->name} ({$group->organization->name})\n";
}

echo "\n=== CONCLUSION ===\n";
if ($user->organizationGroups->count() == 0) {
    echo "✓ Dashboard should show 'You are not a member of any groups yet.'\n";
} else {
    echo "✓ Dashboard should only show the " . $user->organizationGroups->count() . " groups the user is actually assigned to\n";
}

echo "\nIf the user is seeing ALL groups from their organizations instead of just assigned groups,\n";
echo "there might be a caching issue or a different view being used.\n";

?>
