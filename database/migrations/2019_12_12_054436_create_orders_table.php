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
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('address_id')->unsigned();
            $table->integer('cart_id')->unsigned();
            $table->string('order_id');
            $table->float('price');
            $table->string('shipping_charge');
            $table->string('coupon_code')->nullable();
            $table->string('discount')->nullable();
            $table->float('final_amount');
            $table->string('payment_mode');
            $table->string('payment_status');
            $table->tinyInteger('order_status')->comment("0-placed,1-cancelled");
            $table->text('cancel_reason')->nullable();
            $table->bigInteger('deliver_date')->nullable();
            $table->tinyInteger('delivered_status')->comment("1-active,0-inactive(deleted)");
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
            $table->foreign('address_id')->references('id')->on('address')->onDelete("cascade");
            $table->foreign('cart_id')->references('id')->on('cart')->onDelete("cascade");
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
