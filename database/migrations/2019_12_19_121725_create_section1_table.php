<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section1', function (Blueprint $table) {
            $table->increments('id');
            $table->string("image");
            $table->string("text1");
            $table->string("text2");
            $table->string("text3");
            $table->string("text4");
            $table->string("link");
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
        Schema::dropIfExists('section1');
    }
}
