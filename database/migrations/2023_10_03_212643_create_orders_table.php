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
            $table->string('order_code');
            $table->date('date');
            $table->string('order_source')->nullable()->comment('Cashier, Zone, Delivery');
            $table->string('client');
            $table->integer('quantity');
            $table->float('price');
            $table->tinyInteger('status')->default(1)->comment('0=> Pending , 1=> enable, 2=> disable ');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->softDeletes(); // Adds 'deleted_at' column
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
