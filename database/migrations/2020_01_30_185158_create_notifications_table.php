<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("sender_id")->unsigned();
            $table->integer("rec_id")->unsigned();
            $table->string("notification");
            $table->bigInteger("date");
            $table->string("url")->default("javascript:void(0)");
            $table->tinyInteger("read")->default(0)->comment("0-unread,1-read");
            $table->timestamps();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete("cascade");
            $table->foreign('rec_id')->references('id')->on('users')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
