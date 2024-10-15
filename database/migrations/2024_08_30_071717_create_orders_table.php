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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('total_amount',15,2);
            $table->string('payment_method');
            $table->string('payment_status');
            $table->string('shipping_method');
            $table->string('shipping_cost')->default(0);
            $table->decimal('tax_amount')->nullable();
            $table->integer('amount_collected');
            $table->string('receiver_full_name');
            $table->string('address');
            $table->string('phone');
            $table->string('city');
            $table->string('country');
            $table->foreignId('voucher_id')->nullable();
            $table->enum("status" , ["Waiting Confirm" , "Confirmed" , "Pending" , "Cancelled" , "Transporting" , "Done"]);
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
