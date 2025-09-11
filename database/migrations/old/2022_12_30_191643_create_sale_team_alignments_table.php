<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleTeamAlignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_team_alignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sales_rep_id');
            $table->unsignedBigInteger('sale_head_id');
            $table->foreign('sales_rep_id')->references('id')->on('sale_reps');
            $table->foreign('sale_head_id')->references('id')->on('sale_heads');
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
        Schema::dropIfExists('sale_team_alignments');
    }
}
