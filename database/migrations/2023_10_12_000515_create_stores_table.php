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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('tax_registration_number');
            $table->timestamp('open_at')->default('2023-05-01 09:00:00');
            $table->timestamp('close_at')->default('2023-05-05 017:00:00');
            $table->tinyInteger('status')->default(0)->comment('0=> Active , 1=> inactive');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
