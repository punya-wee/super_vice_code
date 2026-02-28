<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('planting_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workspace_id');
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->string('crop_name');
            $table->string('category');
            $table->date('plant_date');
            $table->date('harvest_date');
            $table->decimal('area_rai', 8, 2)->default(0);
            $table->string('status')->default('วางแผนแล้ว'); // วางแผนแล้ว, กำลังปลูก, เก็บเกี่ยวแล้ว
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planting_plans');
    }
};
