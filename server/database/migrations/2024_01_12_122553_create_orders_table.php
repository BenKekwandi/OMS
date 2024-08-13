<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('offer_id')->nullable();
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('shipment_id')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('other_features', 255)->nullable();
            $table->string('reference_number', 255);
            $table->string('name_for_warranty')->nullable();
            $table->integer('matches')->default(0);
            $table->boolean('is_read')->default(false);
            $table->date('confirmed_at')->nullable();
            $table->date('expected_arrival')->nullable();
            $table->date('actual_arrival')->nullable();
            $table->date('shipment_date')->nullable();
            $table->date('expected_delivery_at')->nullable();
            $table->date('finalized_at')->nullable();
            $table->date('deadline');
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('offer_id')
                ->references('id')
                ->on('offers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
