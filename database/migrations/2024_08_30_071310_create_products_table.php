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
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->decimal('price',10,2);
            $table->text('image_url');
            $table->string('status')->nullable();
            $table->boolean('is_variant')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->integer('stock_qty')->default(0)->nullable();
            $table->integer('qty_sold')->default(0)->nullable();
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
