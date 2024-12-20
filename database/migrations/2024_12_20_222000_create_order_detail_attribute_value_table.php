<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_detail_attribute_value', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_detail_id')->constrained('order_details');
            $table->foreignId('attribute_value_id')->constrained('attribute_values');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_detail_attribute_value');
    }
};
