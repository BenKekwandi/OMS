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
        Schema::create('label_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('label_id');
            $table->string('serial_number')->nullable();
            $table->integer('kind')->default(1);
            $table->integer('copies')->default(1);
            $table->date('date');
            $table->softDeletes();


            $table->timestamps();

            $table->foreign('label_id')
                ->references('id')
                ->on('labels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_invoices');
    }
};
