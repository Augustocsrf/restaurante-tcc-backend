<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->date('day');
            $table->time('time');
            $table->string('name');
            $table->string('lastName');
            $table->integer('guests');
            
            $table->unsignedBigInteger('reservation_status');
            $table->foreign('reservation_status')
                ->references('id')
                ->default(1)
                ->on('reservation_statuses')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')
                ->references('id')
                ->on('users')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('reservations');
    }
}
