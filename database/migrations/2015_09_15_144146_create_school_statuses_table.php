<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSchoolStatusesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('school_statuses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id');
			$table->dateTime('last_active')->nullable()->default('0000-00-00 00:00:00');
			$table->integer('status_alarm')->default(0);
			$table->integer('status_police')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('school_statuses');
	}

}
