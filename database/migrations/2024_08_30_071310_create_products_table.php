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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->string('name');
            $table->string('slug');
            $table->decimal('price');
            $table->decimal('sale_price');
            $table->boolean('is_sale')->default(false);
            $table->string('short_description');
            $table->text('description');
            $table->string('sku')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('stock_qty')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
