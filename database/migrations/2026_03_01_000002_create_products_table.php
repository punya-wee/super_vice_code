<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workspace_id');
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->string('name');
            $table->string('category'); // ข้าว, ผลไม้, ผักสด, พืชผล
            $table->decimal('quantity', 10, 2)->default(0);
            $table->string('unit')->default('กิโลกรัม');
            $table->date('harvest_date')->nullable();
            $table->string('status')->default('มีสต็อก'); // มีสต็อก, ใกล้หมด, หมด
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
