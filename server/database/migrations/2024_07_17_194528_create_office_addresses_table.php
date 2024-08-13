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
        Schema::create('office_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('contact_name');
            $table->string('company')->nullable();
            $table->string('street_1');
            $table->string('street_2')->nullable();
            $table->string('street_3')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('post_code');
            $table->string('country');
            $table->string('tax')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_addresses');
    }
};
