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
        Schema::create('proposals', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('offer_id');
            $table->double('sell_price');
            $table->string('notes', 255)->nullable();
            $table->integer('delivery_days')->default(0);
            $table->double('profit')->default(0);
            $table->integer('status')->default(0);
            $table->datetime('applied_at');
            $table->datetime('confirmed_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('offer_id')
                ->references('id')
                ->on('offers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
