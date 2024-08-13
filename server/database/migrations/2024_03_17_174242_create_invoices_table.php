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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('file')->nullable();
            $table->double('amount');
            $table->unsignedBigInteger('invoice_company_id');
            $table->string('invoice_number');
            $table->dateTime('invoicing_date');
            $table->dateTime('payment_deadline');
            $table->boolean('is_customer')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_real')->default(false);
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('invoice_company_id')
                ->references('id')
                ->on('invoice_companies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
