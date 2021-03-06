<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_billing', function (Blueprint $table) {

            $table->increments('id');
            $table->string('firstname')->nullable();
            $table->string('surname')->nullable();
            $table->text('street_1')->nullable();
            $table->string('bte')->nullable();
            $table->string('zip')->nullable();
            $table->string('locale')->nullable();
            $table->string('telephone')->nullable();

            $table->string('city')->nullable();

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('user_billing');
    }
}
