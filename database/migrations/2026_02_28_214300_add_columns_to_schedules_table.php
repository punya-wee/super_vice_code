<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->string('category', 100)->nullable()->after('title');
            $table->decimal('area', 10, 2)->nullable()->after('category');
            $table->decimal('expected_yield', 10, 2)->nullable()->after('area');
            $table->string('status', 50)->default('วางแผนแล้ว')->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['category', 'area', 'expected_yield', 'status']);
        });
    }
};
