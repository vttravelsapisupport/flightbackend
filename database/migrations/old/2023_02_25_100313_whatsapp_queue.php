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
        Schema::create('whatsapp_queue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('summery_id');
            $table->unsignedBigInteger('summery_agent_id');
            $table->timestamps();
            // foreign key references
            $table->foreign('summery_id')->references('id')->on('whatsapp_notification_summery');
            $table->foreign('summery_agent_id')->references('id')->on('whatsapp_notification_summery_agents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
