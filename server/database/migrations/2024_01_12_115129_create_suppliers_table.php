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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 70);
            $table->string('email', 70)->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('primary_name', 70)->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->text('invoice_delivery_rules')->nullable();
            $table->string('tax', 70)->nullable();
            $table->boolean('is_credit')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onUpdate('cascade')
                ->onDelete('set null');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
