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
        Schema::create('agent_credit_shell_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('book_ticket_id');
            $table->unsignedBigInteger('airline_id');
            $table->double('amount',8,2);
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->foreign('book_ticket_id')->references('id')->on('book_tickets');
            $table->foreign('airline_id')->references('id')->on('airlines');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_credit_shell_logs');
    }
};
