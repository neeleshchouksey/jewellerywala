<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubAccessRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_access_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("access_role_id")->unsigned();
            $table->string("title");
            $table->string("link");
            $table->string("active");
            $table->foreign('access_role_id')->references('id')->on('access_roles')->onDelete("cascade");
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
        Schema::dropIfExists('sub_access_roles');
    }
}
