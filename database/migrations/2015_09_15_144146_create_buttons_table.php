<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateButtonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('buttons', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('floor_id');
			$table->float('x', 10)->nullable();
			$table->float('y', 10)->nullable();
			$table->string('button_name', 50);
			$table->string('mac_address');
			$table->string('ip_address');
			$table->string('button_number');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('buttons');
	}

}
