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
        Schema::create('name_list_manager_alignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('airline_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->softDeletes();
            $table->foreign('sector_id')->references('id')->on('destinations');
            $table->foreign('airline_id')->references('id')->on('airlines');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('name_list_manager_alignments');
    }
};
