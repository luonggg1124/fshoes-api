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
        Schema::create('variation_discount', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_id')->constrained('product_variations');
            $table->foreignId('discount_id')->constrained('discounts');
            $table->unique(['variation_id', 'discount_id'],'variation_discount_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variation_discount');
    }
};
