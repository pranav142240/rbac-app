<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\OrganizationGroup;
use App\Models\User;

class OrganizationGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Walkwel Technology organization
        $walkwelTech = Organization::where('slug', 'walkwel-technology')->first();
        
        if ($walkwelTech) {
            // Create technology-specific groups for Walkwel Technology
            $phpGroup = OrganizationGroup::create([
                'organization_id' => $walkwelTech->id,
                'name' => 'PHP Development Team',
                'slug' => 'php-team',
                'description' => 'Backend developers specializing in PHP, Laravel, and related technologies',
                'settings' => [
                    'max_members' => 15,
                    'permissions' => ['backend_development', 'database_management', 'api_development']
                ],
                'is_active' => true,
            ]);

            $jsGroup = OrganizationGroup::create([
                'organization_id' => $walkwelTech->id,
                'name' => 'JavaScript Development Team',
                'slug' => 'js-team',
                'description' => 'Frontend and full-stack developers working with JavaScript, React, Vue, Node.js',
                'settings' => [
                    'max_members' => 20,
                    'permissions' => ['frontend_development', 'ui_development', 'javascript_frameworks']
                ],
                'is_active' => true,
            ]);

            $mobileGroup = OrganizationGroup::create([
                'organization_id' => $walkwelTech->id,
                'name' => 'Mobile Development Team',
                'slug' => 'mobile-team',
                'description' => 'Mobile app developers working with React Native, Flutter, iOS, and Android',
                'settings' => [
                    'max_members' => 12,
                    'permissions' => ['mobile_development', 'app_store_management', 'cross_platform_development']
                ],
                'is_active' => true,
            ]);

            $devOpsGroup = OrganizationGroup::create([
                'organization_id' => $walkwelTech->id,
                'name' => 'DevOps & Infrastructure',
                'slug' => 'devops-team',
                'description' => 'DevOps engineers managing CI/CD, cloud infrastructure, and deployment pipelines',
                'settings' => [
                    'max_members' => 8,
                    'permissions' => ['server_management', 'deployment', 'monitoring', 'security']
                ],
                'is_active' => true,
            ]);

            $uiUxGroup = OrganizationGroup::create([
                'organization_id' => $walkwelTech->id,
                'name' => 'UI/UX Design Team',
                'slug' => 'design-team',
                'description' => 'Designers focused on user interface and user experience design',
                'settings' => [
                    'max_members' => 10,
                    'permissions' => ['design_systems', 'prototyping', 'user_research']
                ],
                'is_active' => true,
            ]);

            $qaGroup = OrganizationGroup::create([
                'organization_id' => $walkwelTech->id,
                'name' => 'Quality Assurance',
                'slug' => 'qa-team',
                'description' => 'QA engineers ensuring software quality through testing and automation',
                'settings' => [
                    'max_members' => 8,
                    'permissions' => ['test_automation', 'manual_testing', 'performance_testing']
                ],
                'is_active' => true,
            ]);

            // Add current user to PHP and JS groups if exists
            $currentUser = User::first();
            if ($currentUser) {
                $phpGroup->addMember($currentUser);
                $jsGroup->addMember($currentUser);
            }
        }

        // Create general groups for other organizations
        $otherOrganizations = Organization::where('slug', '!=', 'walkwel-technology')->get();

        foreach ($otherOrganizations as $organization) {
            // Create some sample groups for each organization
            OrganizationGroup::create([
                'organization_id' => $organization->id,
                'name' => 'Development Team',
                'slug' => 'development-team-' . $organization->slug,
                'description' => 'Software development and engineering team',
                'settings' => [
                    'max_members' => 20,
                    'permissions' => ['code_review', 'deploy_staging']
                ],
                'is_active' => true,
            ]);

            OrganizationGroup::create([
                'organization_id' => $organization->id,
                'name' => 'Marketing Department',
                'slug' => 'marketing-department-' . $organization->slug,
                'description' => 'Marketing and communications team',
                'settings' => [
                    'max_members' => 15,
                    'permissions' => ['manage_campaigns', 'view_analytics']
                ],
                'is_active' => true,
            ]);

            OrganizationGroup::create([
                'organization_id' => $organization->id,
                'name' => 'Operations Team',
                'slug' => 'operations-team-' . $organization->slug,
                'description' => 'Operations and administrative team',
                'settings' => [
                    'max_members' => 10,
                    'permissions' => ['manage_operations', 'view_reports']
                ],
                'is_active' => true,
            ]);
        }

        $this->command->info('Organization groups created successfully!');
        $this->command->info('Walkwel Technology groups:');
        $this->command->info('- PHP Development Team');
        $this->command->info('- JavaScript Development Team');
        $this->command->info('- Mobile Development Team');
        $this->command->info('- DevOps & Infrastructure');
        $this->command->info('- UI/UX Design Team');
        $this->command->info('- Quality Assurance');
    }
}
