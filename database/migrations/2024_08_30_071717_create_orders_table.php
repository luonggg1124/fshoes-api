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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount',15,2);
            $table->string('payment_method');
            $table->string('payment_status');
            $table->string('shipping_method');
            $table->string('shipping_cost')->default(0);
            $table->decimal('tax_amount')->nullable();
            $table->integer('amount_collected');
            $table->string('receiver_full_name');
            $table->string('receiver_email');
            $table->string('address');
            $table->string('phone');
            $table->string('city');
            $table->string('country');
            $table->foreignId('voucher_id')->nullable();
            $table->integer("status")->comment("0: Cancelled, 1: Waiting Confirm, 2: Confirmed, 3: Delivering , 4: Delivered , 5: Return Processing, 6: Denied Return, 7: Returned");
            $table->text("reason_cancelled")->nullable();
            $table->text('note')->nullable();
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
