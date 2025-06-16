<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run permission seeder first to create roles and admin user
        $this->call([
            PermissionSeeder::class,
            OrganizationSeeder::class,
            OrganizationGroupSeeder::class,
            TestUsersSeeder::class,
        ]);
        
        // Note: Admin user is created in PermissionSeeder with proper role assignment
        // Email: admin@example.com, Password: Admin@12345
        $this->command->info('Seeding completed successfully!');
        $this->command->info('Organizations, groups, and test users have been created.');
        $this->command->info('Test users password: password123');
    }
}
