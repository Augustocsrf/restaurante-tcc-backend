<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->double('price');
            $table->tinyInteger('payment_method');
            $table->double('cash')->nullable();
            $table->tinyInteger('delivery_method');


            $table->unsignedBigInteger('order_status_id');
            $table->foreign('order_status_id')
                ->references('id')
                ->on('order_statuses')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('restrict');

            //Endereço
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')
                ->references('id')
                ->on('addresses')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('set null');

            //Cliente
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('users')
                ->constrained()
                ->onUpdate('cascade');
            
            
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
        Schema::dropIfExists('orders');
    }
}
