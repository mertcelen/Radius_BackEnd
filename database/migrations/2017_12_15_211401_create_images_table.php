<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imageId');
            $table->string('userId')->nullable();
            $table->boolean('isValid')->default(false);
            $table->integer('red1')->nullable()->default(null);
            $table->integer('green1')->nullable()->default(null);
            $table->integer('blue1')->nullable()->default(null);
            $table->integer('red2')->nullable()->default(null);
            $table->integer('green2')->nullable()->default(null);
            $table->integer('blue2')->nullable()->default(null);
            $table->integer('red3')->nullable()->default(null);
            $table->integer('green3')->nullable()->default(null);
            $table->integer('blue3')->nullable()->default(null);
            $table->string('labels1')->nullable()->default(null);
            $table->string('labels2')->nullable()->default(null);
            $table->string('labels3')->nullable()->default(null);
            $table->float('time1')->nullable();
            $table->float('time2')->nullable();
            $table->float('time3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
