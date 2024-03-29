<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')
                    ->nullable();
            $table->string('password');

            $table->string('phone')
                    ->nullable();
            $table->tinyInteger('permission')
                    ->default(1)
                    ->comment("1 - Cliente; 2 - Funcionárion; 3 - Admin");
            $table->string('google_id')
                    ->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
