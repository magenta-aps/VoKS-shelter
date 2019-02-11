<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSchoolDefaultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('school_defaults', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('timezone');
			$table->string('locale');
			$table->string('ordering', 50);
			$table->string('sms_provider');
			$table->string('phone_system_provider');
			$table->string('user_data_source')->nullable();
			$table->string('client_data_source')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('school_defaults');
	}

}
