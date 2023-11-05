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
        Schema::create('transfer_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_inventory_id');
            $table->unsignedBigInteger('receiver_inventory_id');
            $table->unsignedBigInteger('receiver_branch_id');
            $table->unsignedBigInteger('receiver_user_id');
            $table->unsignedBigInteger('inventory_item_id');
            $table->integer('quantity');
            $table->tinyInteger('status')->default(1)->comment('0=> Draft , 1=> hanging, 2=> acceptable, 3=> unacceptable,4=> closed');

            $table->softDeletes(); // Adds 'deleted_at' column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_orders');
    }
};
