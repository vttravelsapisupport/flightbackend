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
        Schema::create('flight_ticket_live_p_n_r_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->integer('total_pax_count');
            $table->string('flight_no');
            $table->date('travel_date');
            $table->string('source');
            $table->string('destination');
            $table->string('dep_time');
            $table->string('arrival_time');
            $table->string('current_flight_status');
            $table->string('pnr_status');
            $table->text('passengers');
            $table->smallInteger('status');

            $table->foreign('purchase_id')
                        ->references('id')
                        ->on('flightticket_purchase');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flight_ticket_live_p_n_r_statuses');
    }
};
