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
        Schema::create('airline_cancellation_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('airline_id');
            $table->unsignedBigInteger('slot_id');
            $table->float('amount');
            $table->float('int_amount');
            $table->timestamps();
            $table->foreign('airline_id')->references('id')->on('airlines');
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
        Schema::dropIfExists('airline_cancellation_policies');
    }
};
