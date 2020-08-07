<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("seller_id")->unsigned();
            $table->string("product_id");
            $table->string("product_id_manual");
            $table->string("product_name");
            $table->integer("category_id")->unsigned();
            $table->integer("sub_category_id")->unsigned();
            $table->integer("purity_id")->unsigned();
            $table->integer("occasion_id")->unsigned();
            $table->tinyInteger("product_for")->comment("1-male,2-female");
            $table->tinyInteger("tax")->comment("1-include,2-exclude");
            $table->string("front_image");
            $table->tinyInteger("verified_by_admin")->comment("0-not verified,1-verified")->default(0);
            $table->foreign('seller_id')->references('id')->on('users')->onDelete("cascade");
            $table->foreign('category_id')->references('id')->on('categories')->onDelete("cascade");
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete("cascade");
            $table->foreign('purity_id')->references('id')->on('purities')->onDelete("cascade");
            $table->foreign('occasion_id')->references('id')->on('occasion')->onDelete("cascade");
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
        Schema::dropIfExists('products');
    }
}
