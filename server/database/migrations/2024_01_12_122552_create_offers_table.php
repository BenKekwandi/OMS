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
        Schema::create('offers', function (Blueprint $table) {

            $table->id();
            $table->integer('order_days')->default(0);
            $table->integer('availability');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('reference_number', 255);
            $table->string('image', 255)->nullable();
            $table->string('other_features', 255)->nullable();
            $table->double('discount')->default(0);
            $table->double('net_price')->default(0);
            $table->double('rrp_price')->default(0);
            $table->string('rrp_explanation', 255)->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('serial_number', 255)->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
