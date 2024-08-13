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
        Schema::create('shipment_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_service_id');
            $table->string('title')->nullable();
            $table->string('address')->nullable();
            $table->string('postmen_id')->nullable();
            $table->timestamps();

            $table->foreign('shipment_service_id')
                ->references('id')
                ->on('shipment_services')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_accounts');
    }
};
