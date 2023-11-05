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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->tinyInteger('item_type')->nullable()->default(1)->comment('0=> item , 1=> product');;
            $table->string('code');
          //$table->unsignedBigInteger('inventory_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->integer('storage_unit')->nullable();
            $table->float('conversion_factor')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->decimal('min_level', 10, 2)->nullable();
            $table->decimal('max_level', 10, 2)->nullable();
            $table->decimal('reorder_level', 10, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
