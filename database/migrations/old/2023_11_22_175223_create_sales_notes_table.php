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
        Schema::create('sales_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_ticket_id');
            $table->foreign('book_ticket_id')->references('id')->on('book_tickets');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('notes');
            $table->boolean('isActive')->default(1)->comment('1 is active and 0 is inactive');
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
        Schema::dropIfExists('sales_notes');
    }
};
