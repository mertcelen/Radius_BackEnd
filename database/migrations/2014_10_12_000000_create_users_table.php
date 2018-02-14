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
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->boolean('isInstagram')->default(false);
            $table->string('instagram_id')->unique()->nullable();
            //0 => unconfirmed, 1 => confirmed, 2 =>banned, 3=> admin
            $table->integer('status')->default(0);
            $table->string('secret')->unique()->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('favoriteList')->nullable();
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
