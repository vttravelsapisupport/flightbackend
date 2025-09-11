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
        Schema::create('agent_markups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->double('markup_price',6,2);
            $table->boolean('status')->default(1)->comment(' 0 is inactive and 1 is active');
            $table->foreign('agent_id')->references('id')->on('agents');
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
        Schema::dropIfExists('agent_markups');
    }
};
