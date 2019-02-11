<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGotItHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('got_it_history', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id')->unsigned()->index('school_id');
			$table->integer('notification_id')->unsigned()->index('notification_id');
			$table->integer('device_id')->unsigned()->index('device_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('got_it_history');
	}

}
