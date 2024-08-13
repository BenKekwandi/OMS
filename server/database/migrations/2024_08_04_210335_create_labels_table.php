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
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id');
            $table->integer('status')->default(1);
            $table->integer('kind')->default(1);
            $table->string('file');
            $table->float('amount')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('postmen_id')->nullable();
            $table->date('expected_collection_at')->nullable();
            $table->date('expected_delivery_at')->nullable();
            $table->text('response')->nullable();
            $table->softDeletes();


            $table->timestamps();

            $table->foreign('shipment_id')
                ->references('id')
                ->on('shipments')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labels');
    }
};
