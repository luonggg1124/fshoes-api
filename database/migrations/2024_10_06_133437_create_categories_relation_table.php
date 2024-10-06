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
        Schema::create('categories_relation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('child_id')->constrained('categories')->onDelete('cascade');
            $table->unique(['parent_id', 'child_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_relation');
    }
};
