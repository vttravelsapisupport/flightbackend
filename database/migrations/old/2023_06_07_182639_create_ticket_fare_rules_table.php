<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_fare_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_entry_id');
            $table->unsignedBigInteger('slot_id');
            $table->unsignedBigInteger('slot_desc');
            $table->float('domestic_amount');
            $table->float('international_amount');
            $table->timestamps();
            $table->foreign('purchase_entry_id')->references('id')->on('purchase_entries');
            $table->foreign('slot_id')->references('id')->on('cancellation_slots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_fare_rules');
    }
};
