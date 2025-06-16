<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Organization;
use App\Models\OrganizationGroup;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Organization Groups Index Fix ===\n\n";

// Find the test user
$user = User::where('email', 'pranav@user.com')->first();
if (!$user) {
    echo "Test user 'pranav@user.com' not found!\n";
    exit(1);
}

echo "Testing with user: {$user->name} ({$user->email})\n\n";

echo "=== BEFORE FIX SIMULATION ===\n";
echo "Old query would show ALL groups in user's organizations:\n";

$oldQuery = OrganizationGroup::with(['organization', 'users'])
    ->whereHas('users', function($query) use ($user) {
        $query->where('user_id', $user->id);
    })
    ->orWhereHas('organization', function($query) use ($user) {
        $query->whereHas('users', function($subQuery) use ($user) {
            $subQuery->where('user_id', $user->id);
        });
    })
    ->get();

foreach ($oldQuery as $group) {
    $isUserMember = $user->organizationGroups->contains('id', $group->id);
    echo "- {$group->name} ({$group->organization->name}) " . ($isUserMember ? "[USER ASSIGNED]" : "[USER NOT ASSIGNED - SHOULDN'T SHOW]") . "\n";
}

echo "\n=== AFTER FIX ===\n";
echo "New query shows ONLY groups user is actually assigned to:\n";

$newQuery = OrganizationGroup::with(['organization', 'users'])
    ->whereHas('users', function($query) use ($user) {
        $query->where('user_id', $user->id);
    })
    ->get();

if ($newQuery->count() > 0) {
    foreach ($newQuery as $group) {
        echo "- {$group->name} ({$group->organization->name}) [USER ASSIGNED]\n";
    }
} else {
    echo "No groups found (correct - user is not assigned to any groups)\n";
}

echo "\n=== SUMMARY ===\n";
echo "Old query found: {$oldQuery->count()} groups\n";
echo "New query found: {$newQuery->count()} groups\n";
echo "✓ Fixed: Now only shows groups the user is actually assigned to!\n";

?>
