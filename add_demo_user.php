<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    // Check if test user already exists
    $existingUser = DB::table('users')->where('email', 'demo@agri.local')->first();
    
    if ($existingUser) {
        echo "Test user already exists!\n";
        echo "Email: demo@agri.local\n";
        echo "Password: password123\n";
    } else {
        // Create hashed password (using bcrypt)
        $hashedPassword = Hash::make('password123');
        
        // Insert the test user
        DB::table('users')->insert([
            'full_name' => 'Demo User',
            'email' => 'demo@agri.local',
            'password_hash' => $hashedPassword,
            'role' => 'member',
            'created_at' => now(),
        ]);
        
        echo "Test user created successfully!\n";
        echo "Email: demo@agri.local\n";
        echo "Password: password123\n";
    }
    
    // List all users
    echo "\nAll users in database:\n";
    $users = DB::table('users')->get();
    foreach ($users as $user) {
        echo "- {$user->email} ({$user->full_name}) - Role: {$user->role}\n";
    }
    
} catch (\Exception $e) {
    echo "Error: {$e->getMessage()}\n";
}
?>
