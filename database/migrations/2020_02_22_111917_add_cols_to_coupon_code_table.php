<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToCouponCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_code', function (Blueprint $table) {
            $table->string("min_price");
            $table->string("max_price");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_code', function (Blueprint $table) {
            $table->dropColumn("min_price");
            $table->dropColumn("max_price");
        });
    }
}
