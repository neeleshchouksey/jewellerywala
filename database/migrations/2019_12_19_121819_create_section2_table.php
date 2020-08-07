<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section2', function (Blueprint $table) {
            $table->increments('id');
            $table->string("image1");
            $table->string("link1");
            $table->string("image2");
            $table->string("link2");
            $table->string("image3");
            $table->string("link3");
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
        Schema::dropIfExists('section2');
    }
}
