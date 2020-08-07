<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string("shop_name");
            $table->string("address1");
            $table->string("address2");
            $table->integer("city");
            $table->integer("state");
            $table->integer("pin_code");
            $table->string("gst_number")->nullable();
            $table->string("pan_number")->nullable();
            $table->string("bank_branch_name")->nullable();
            $table->string("bank_branch_address")->nullable();
            $table->string("bank_account_number")->nullable();
            $table->string("bank_ifsc_code")->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
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
        Schema::dropIfExists('shops');
    }
}
