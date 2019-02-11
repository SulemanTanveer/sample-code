<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->uuid('reference');
			
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			
			$table->integer('child_id')->unsigned()->nullable();
			$table->foreign('child_id')->references('id')->on('children')->onDelete('cascade');
			
			$table->integer('address_id')->unsigned();
			$table->foreign('address_id')->references('id')->on('user_addresses')->onDelete('cascade');
			
			$table->integer('shipment_id')->unsigned();
			$table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');

            $table->string('transaction_id')->nullable();
			$table->integer('order_status_id');
			$table->string('payment');

			$table->float('discount');
			$table->float('total');			
			
			$table->string('comment')->nullable();
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
		Schema::drop('orders');
	}

}
