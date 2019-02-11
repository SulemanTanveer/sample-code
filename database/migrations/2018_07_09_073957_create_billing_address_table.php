<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_address', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('firstname')->nullable();
            $table->string('surname')->nullable();
            $table->text('street_1')->nullable();
            $table->string('bte')->nullable();
            $table->string('zip')->nullable();
            $table->string('locale')->nullable();
            $table->string('telephone')->nullable();

            $table->string('city')->nullable();
            
            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            
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
        Schema::dropIfExists('billing_address');
    }
}
