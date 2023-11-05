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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('reference_number');
            $table->string('tax_group');
            $table->string('branch_tax');
            $table->string('branch_tax_number');
            $table->string('beginning_work');
            $table->string('end_work');
            $table->string('end_day_inventory');
            $table->string('phone');
            $table->string('address');
            $table->string('street_name');
            $table->string('building_number');
            $table->string('extension_number');
            $table->string('city');
            $table->string('district');
            $table->string('postal_code');
            $table->string('commercial_registration_number');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('order_viewer_application');
            $table->string('top_of_invoice');
            $table->string('bottom_of_invoice');
            $table->tinyInteger('status')->default(1)->comment('0=> Active , 1=> inactive');
            $table->unsignedBigInteger('store_id'); // Foreign key to link to the users table
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes(); // Adds 'deleted_at' column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
