<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Running Organization and Group Seeders...\n";
    
    // Run specific seeders
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\OrganizationSeeder']);
    echo "✓ Organization seeder completed\n";
    
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\OrganizationGroupSeeder']);
    echo "✓ Organization Group seeder completed\n";
    
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\TestUsersSeeder']);
    echo "✓ Test Users seeder completed\n";
    
    echo "\nSeeding completed successfully!\n";
    echo "Organizations created:\n";
    echo "- Walkwel Technology\n";
    echo "- Innovation Corp\n";
    echo "- Digital Solutions Agency\n\n";
    
    echo "Groups created for Walkwel Technology:\n";
    echo "- PHP Development Team\n";
    echo "- JavaScript Development Team\n";
    echo "- Mobile Development Team\n";
    echo "- DevOps & Infrastructure\n";
    echo "- UI/UX Design Team\n";
    echo "- Quality Assurance\n\n";
    
    echo "Test users created with password: password123\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
