<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger("new_arrival")->default(0)->comment("0-not added,1-added");
            $table->tinyInteger("featured")->default(0)->comment("0-not added,1-added");
            $table->tinyInteger("trending")->default(0)->comment("0-not added,1-added");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn("new_arrival");
            $table->dropColumn("featured");
            $table->dropColumn("trending");
        });
    }
}
