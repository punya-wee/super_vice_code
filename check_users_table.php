<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Get table structure
    echo "Users Table Structure:\n";
    echo "=======================\n";
    
    $columns = DB::select('DESCRIBE users');
    
    foreach ($columns as $column) {
        echo sprintf("- %s (%s) %s\n", 
            $column->Field,
            $column->Type,
            $column->Null === 'YES' ? 'NULL' : 'NOT NULL'
        );
    }
    
    // List existing users
    echo "\nExisting Users:\n";
    echo "=======================\n";
    $users = DB::table('users')->get();
    if ($users->isEmpty()) {
        echo "No users found\n";
    } else {
        foreach ($users as $user) {
            echo "- " . json_encode($user) . "\n";
        }
    }
    
} catch (\Exception $e) {
    echo "Error: {$e->getMessage()}\n";
}
?>
