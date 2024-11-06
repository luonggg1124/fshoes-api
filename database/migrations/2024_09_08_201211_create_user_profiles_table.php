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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users');
            $table->foreignId('address_active_id')->nullable()->constrained('user_addresses');
            $table->string('nation')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('commune')->nullable();
            $table->string('detail_address')->nullable();
            $table->string('given_name')->nullable();
            $table->string('family_name')->nullable();
            $table->timestamp('birth_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
