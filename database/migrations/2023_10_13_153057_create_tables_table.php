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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('seats');
            $table->enum('status', ['available', 'reserved', 'occupied'])->default('available');
            $table->unsignedBigInteger('zone_id');
//            $table->string('table_number')->unique();
//            $table->integer('seating_capacity');
//            $table->string('location')->nullable();
//            $table->dateTime('reservation_time')->nullable();
//            $table->json('reservation_info')->nullable();
//            $table->json('order_info')->nullable();
//            $table->boolean('is_occupied')->default(false);
//            $table->text('additional_notes')->nullable();
//            $table->unsignedBigInteger('branch_id')->nullable();
//            $table->unsignedBigInteger('store_id');
            $table->softDeletes(); // Adds 'deleted_at' column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
