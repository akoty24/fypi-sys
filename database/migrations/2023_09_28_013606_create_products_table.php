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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('photo');
            $table->boolean('is_retail')->default(false);
            $table->string('product_code');
            $table->string('pricing_method');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('tax_group')->nullable();
            $table->string('cost_calculation_method');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('selling_unit');
            $table->unsignedBigInteger('category_id'); // Foreign key to link to the users table
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->tinyInteger('status')->default(1)->comment('0=> Found , 1=> not found');

            $table->softDeletes(); // Adds 'deleted_at' column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
