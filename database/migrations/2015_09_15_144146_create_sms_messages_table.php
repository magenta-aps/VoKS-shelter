<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSmsMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sms_messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id')->unsigned()->index('school_id');
			$table->text('initial_message', 65535)->nullable();
			$table->text('crisis_team_message', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sms_messages');
	}

}
