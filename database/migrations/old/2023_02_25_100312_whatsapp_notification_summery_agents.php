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
        Schema::create('whatsapp_notification_summery_agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('summery_id');
            $table->unsignedBigInteger('agent_id');
            $table->string('intrakt_id')->default('')->comment('intrakt queue id');
            $table->integer('is_sent')->default(0)->comment('webhook delivery response');
            $table->timestamps();
            // foreign key references
            $table->foreign('summery_id')->references('id')->on('whatsapp_notification_summery');
            $table->foreign('agent_id')->references('id')->on('agents');
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
