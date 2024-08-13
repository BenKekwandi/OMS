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
        Schema::create('invoice_companies', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('country');
            $table->text('location');
            $table->string('phone')->nullable();
            $table->string('contact_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_companies');
    }
};
