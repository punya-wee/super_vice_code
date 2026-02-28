<?php
// Add test user to database
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    // Check if test user already exists
    $existingUser = User::where('email', 'demo@agri.local')->first();
    
    if ($existingUser) {
        echo "Test user already exists!\n";
        echo "Email: demo@agri.local\n";
        echo "Password: password123\n";
    } else {
        // Create new test user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@agri.local',
            'password' => Hash::make('password123'),
        ]);
        
        echo "Test user created successfully!\n";
        echo "Email: demo@agri.local\n";
        echo "Password: password123\n";
        echo "User ID: {$user->id}\n";
    }
    
    // List all users
    echo "\nAll users in database:\n";
    $users = User::all();
    foreach ($users as $user) {
        echo "- {$user->email} (ID: {$user->id})\n";
    }
} catch (\Exception $e) {
    echo "Error: {$e->getMessage()}\n";
}
?>
