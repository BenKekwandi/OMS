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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_account_id');
            $table->enum('shipping_type', ['outgoing', 'incoming']);
            $table->boolean('automatic_shipping')->default(false);
            $table->integer('status')->default(1);
            $table->string('ship_to_title');
            $table->string('ship_from_title');
            $table->unsignedBigInteger('ship_to_id')->nullable();
            $table->unsignedBigInteger('ship_from_id')->nullable();
            $table->float('box_weight')->nullable();
            $table->float('box_width')->nullable();
            $table->float('box_height')->nullable();
            $table->float('box_depth')->nullable();
            $table->date('pick_up_time');
            $table->date('deadline');
            $table->date('collected_at')->nullable();
            $table->date('delivered_at')->nullable();
            $table->softDeletes();


            $table->timestamps();

            $table->foreign('shipment_account_id')
                ->references('id')
                ->on('shipment_accounts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ship_to_id')
                ->references('id')
                ->on('office_addresses')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('ship_from_id')
                ->references('id')
                ->on('office_addresses')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippments');
    }
};
