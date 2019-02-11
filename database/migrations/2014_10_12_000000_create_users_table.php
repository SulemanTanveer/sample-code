<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('password')->nullable();
            $table->string('email')->unique();
            $table->integer('status')->nullable();
            $table->rememberToken();
            $table->string('confirmation_token')->nullable();
            $table->timestamp('last_active')->nullable();
            $table->boolean('promotion_offer')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

}
