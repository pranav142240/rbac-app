<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\User;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        // Create Walkwel Technology organization
        $walkwelTech = Organization::create([
            'name' => 'Walkwel Technology',
            'slug' => 'walkwel-technology',
            'description' => 'Leading technology company specializing in web development, mobile applications, and enterprise solutions.',
            'website' => 'https://walkweltech.com',
            'email' => 'info@walkweltech.com',
            'phone' => '+1-555-0123',
            'address' => '123 Tech Park, Silicon Valley, CA 95014',
            'settings' => [
                'max_members' => 100,
                'requires_approval' => false,
                'allow_public_join' => false,
                'default_role' => 'member'
            ],
            'is_active' => true,
        ]);

        // Create additional technology companies
        $techCorp = Organization::create([
            'name' => 'Innovation Corp',
            'slug' => 'innovation-corp',
            'description' => 'Innovative solutions for modern businesses with cutting-edge technology.',
            'website' => 'https://innovationcorp.com',
            'email' => 'contact@innovationcorp.com',
            'phone' => '+1-555-0456',
            'address' => '456 Innovation Blvd, Austin, TX 78701',
            'settings' => [
                'max_members' => 50,
                'requires_approval' => true,
                'allow_public_join' => false,
                'default_role' => 'member'
            ],
            'is_active' => true,
        ]);

        $digitalAgency = Organization::create([
            'name' => 'Digital Solutions Agency',
            'slug' => 'digital-solutions-agency',
            'description' => 'Full-service digital agency providing web design, development, and marketing services.',
            'website' => 'https://digitalsolutions.com',
            'email' => 'hello@digitalsolutions.com',
            'phone' => '+1-555-0789',
            'address' => '789 Digital Ave, New York, NY 10001',
            'settings' => [
                'max_members' => 30,
                'requires_approval' => false,
                'allow_public_join' => true,
                'default_role' => 'contributor'
            ],
            'is_active' => true,
        ]);

        // Add the current user (if exists) to Walkwel Technology as admin
        $currentUser = User::first();
        if ($currentUser) {
            $walkwelTech->addMember($currentUser);
            $techCorp->addMember($currentUser);
        }

        $this->command->info('Organizations created successfully!');
        $this->command->info('- Walkwel Technology');
        $this->command->info('- Innovation Corp');
        $this->command->info('- Digital Solutions Agency');
    }
}
