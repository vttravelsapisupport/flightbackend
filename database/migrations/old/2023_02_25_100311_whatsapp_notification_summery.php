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
        Schema::create('whatsapp_notification_summery', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ex');
            $table->integer('days');
            $table->text('sector_ids')->comment('plain json array');
            $table->text('airport_ids')->comment('plain json array');
            $table->string('template_name')->comment('intrakt template name');
            $table->text('body_values')->comment('plain json array');
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
        //
    }
};
