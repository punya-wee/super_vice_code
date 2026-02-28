<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'demo@agri.local')->first();

if ($user) {
    echo "✓ User found!\n";
    echo "Email: {$user->email}\n";
    echo "Name: {$user->full_name}\n";
    echo "Role: {$user->role}\n";
    echo "\nPassword verification:\n";
    
    // Test password
    $testPassword = 'password123';
    $verified = Hash::check($testPassword, $user->password_hash);
    echo "Password 'password123': " . ($verified ? "✓ OK\n" : "✗ FAILED\n");
} else {
    echo "✗ User not found\n\n";
    echo "All users in database:\n";
    $users = User::all();
    foreach ($users as $u) {
        echo "- {$u->email} ({$u->full_name})\n";
    }
}
?>
