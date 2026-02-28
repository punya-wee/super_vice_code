<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('price_records', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->date('recorded_date');
            $table->string('source')->default('ตลาดกลาง');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_records');
    }
};
