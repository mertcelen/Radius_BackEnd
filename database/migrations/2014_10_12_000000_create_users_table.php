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
            $table->string('verification')->nullable();
            $table->boolean('isInstagram')->default(false);
            $table->string('instagram_id')->unique()->nullable();
            $table->string('avatar')->nullable()->default('default_avatar');
            $table->integer('status')->default(0);
            $table->string('secret')->unique()->nullable();
            $table->string('faagramId')->nullable();
            $table->boolean('female')->default(true);
            $table->string('values')->default('50,25,25');
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
