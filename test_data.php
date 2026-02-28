<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();
$db->addConnection([
    'driver'    => 'mysql',
    'host'      => env('DB_HOST', '127.0.0.1'),
    'database'  => env('DB_DATABASE', 'agri_management_system'),
    'username'  => env('DB_USERNAME', 'root'),
    'password'  => env('DB_PASSWORD', ''),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

$db->setAsGlobal();
$db->bootEloquent();

try {
    // ลองดึง list ตาราง
    $tables = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?", [env('DB_DATABASE')]);
    
    echo "✓ Database: " . env('DB_DATABASE') . "\n";
    echo "✓ Host: " . env('DB_HOST') . "\n";
    
    if (count($tables) === 0) {
        echo "⚠ Database มีอยู่แต่ไม่มีตาราง (ว่างเปล่า)\n";
    } else {
        echo "\nตารางที่มีอยู่:\n";
        foreach ($tables as $table) {
            echo "  - " . $table->TABLE_NAME . "\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
