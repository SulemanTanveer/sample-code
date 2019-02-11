<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('children', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname')->nullable();
//            $table->string('surname')->nullable();
            $table->string('picture')->nullable();
            $table->boolean('sexe')->nullable();
            $table->dateTime('birthdate')->nullable();

            $table->integer('parent_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');

            $table->integer('school_id')->unsigned();
           $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            $table->integer('school_level_id')->unsigned();
            $table->foreign('school_level_id')->references('id')->on('school_levels')->onDelete('cascade');

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
        Schema::dropIfExists('children');
    }
}
