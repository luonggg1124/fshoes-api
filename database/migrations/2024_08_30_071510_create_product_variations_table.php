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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->string('code_identifier')->nullable();
            $table->string('name')->nullable();
            $table->string('classify')->nullable();
            $table->string('sku')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->decimal('price',10,2);
            $table->integer('stock_qty');
            $table->integer('qty_sold');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
