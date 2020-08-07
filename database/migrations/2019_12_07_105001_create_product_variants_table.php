<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("product_id")->unsigned();;
            $table->integer("color_id")->unsigned();
            $table->text('short_description');
            $table->longText('long_description');
            $table->string("size");
            $table->string("weight");
            $table->float("mrp");
            $table->float("discount");
            $table->float("selling_price");
            $table->foreign('product_id')->references('id')->on('products')->onDelete("cascade");
            $table->foreign('color_id')->references('id')->on('color')->onDelete("cascade");
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
        Schema::dropIfExists('product_variants');
    }
}
