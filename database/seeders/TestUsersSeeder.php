<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserAuthMethod;
use App\Models\Organization;
use App\Models\OrganizationGroup;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $walkwelTech = Organization::where('slug', 'walkwel-technology')->first();
        
        if (!$walkwelTech) {
            $this->command->error('Walkwel Technology organization not found. Run OrganizationSeeder first.');
            return;
        }

        // Create test users
        $users = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@walkweltech.com',
                'phone' => '+1-555-1001',
                'groups' => ['php-team', 'devops-team']
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@walkweltech.com',
                'phone' => '+1-555-1002',
                'groups' => ['js-team', 'design-team']
            ],
            [
                'name' => 'Mike Davis',
                'email' => 'mike.davis@walkweltech.com',
                'phone' => '+1-555-1003',
                'groups' => ['mobile-team', 'qa-team']
            ],
            [
                'name' => 'Emily Wilson',
                'email' => 'emily.wilson@walkweltech.com',
                'phone' => '+1-555-1004',
                'groups' => ['design-team', 'js-team']
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@walkweltech.com',
                'phone' => '+1-555-1005',
                'groups' => ['php-team', 'devops-team']
            ],
            [
                'name' => 'Lisa Garcia',
                'email' => 'lisa.garcia@walkweltech.com',
                'phone' => '+1-555-1006',
                'groups' => ['qa-team', 'mobile-team']
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'robert.taylor@walkweltech.com',
                'phone' => '+1-555-1007',
                'groups' => ['js-team', 'php-team']
            ],
            [
                'name' => 'Anna Martinez',
                'email' => 'anna.martinez@walkweltech.com',
                'phone' => '+1-555-1008',
                'groups' => ['design-team']
            ]
        ];        foreach ($users as $userData) {
            // Check if user already exists
            $existingUser = User::where('email', $userData['email'])->first();
            if ($existingUser) {
                $this->command->info("User {$userData['email']} already exists, skipping...");
                continue;
            }

            // Create user
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'password' => Hash::make('password123'),
                'primary_auth_method' => 'email_password',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]);// Create auth methods
            UserAuthMethod::create([
                'user_id' => $user->id,
                'auth_method_type' => 'email_password',
                'identifier' => $userData['email'],
                'auth_method_verified_at' => now(),
                'is_active' => true,
            ]);

            UserAuthMethod::create([
                'user_id' => $user->id,
                'auth_method_type' => 'phone_password',
                'identifier' => $userData['phone'],
                'auth_method_verified_at' => now(),
                'is_active' => true,
            ]);            // Add user to organization
            $walkwelTech->addMember($user);

            // Assign default "Application User" role
            $applicationUserRole = Role::where('name', 'Application User')->first();
            if ($applicationUserRole) {
                $user->assignRole($applicationUserRole);
            }

            // Add user to specified groups
            foreach ($userData['groups'] as $groupSlug) {
                $group = OrganizationGroup::where('slug', $groupSlug)
                                        ->where('organization_id', $walkwelTech->id)
                                        ->first();
                if ($group) {
                    $group->addMember($user);
                }
            }

            $this->command->info("Created user: {$userData['name']} ({$userData['email']})");
        }

        $this->command->info('Test users created successfully!');
        $this->command->info('All users have password: password123');
        $this->command->info('Users have been added to Walkwel Technology and their respective groups.');
    }
}
