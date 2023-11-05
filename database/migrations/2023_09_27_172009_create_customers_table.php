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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique()->nullable();
            $table->unsignedBigInteger('system_point')->default(0);
            $table->unsignedFloat('balance')->default(0.0);
            $table->string('country_code', 5)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=> Active , 1=> inactive');
            //verfied date
            $table->timestamp('email_verified_at')->nullable();
            $table->softDeletes(); // Adds 'deleted_at' column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
