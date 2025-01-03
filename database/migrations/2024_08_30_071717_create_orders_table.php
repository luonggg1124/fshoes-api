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
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->decimal('total_amount',15,2);
            $table->string('payment_method');
            $table->string('payment_status');
            $table->string('shipping_method')->nullable();
            $table->string('shipping_cost')->default(0);
            $table->decimal('tax_amount')->nullable();
            $table->integer('amount_collected');
            $table->string('receiver_full_name')->nullable();
            $table->string('receiver_email')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->foreignId('voucher_id')->nullable();
            $table->integer("status")->comment("0: Cancelled, 1: Waiting Payment , 2: Waiting Confirm, 3: Confirmed, 4: Delivering , 5: Delivered , 6: Waiting Accept Return ,  7:Return Processing, 8: Denied Return, 9: Returned");
            $table->text("reason_cancelled")->nullable();
            $table->text("reason_return")->nullable();
            $table->text("reason_denied_return")->nullable();
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
