<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For enum modifications, raw DB statement is safer and often required if doctrine/dbal is missing
        DB::statement("ALTER TABLE schedules MODIFY status ENUM('วางแผนแล้ว', 'กำลังปลูก', 'เก็บเกี่ยวแล้ว') NOT NULL DEFAULT 'วางแผนแล้ว'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE schedules MODIFY status VARCHAR(50) NOT NULL DEFAULT 'วางแผนแล้ว'");
    }
};
