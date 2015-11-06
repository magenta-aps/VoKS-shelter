<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePushNotificationMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('push_notification_messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id')->unsigned()->index('school_id');
			$table->text('label', 65535)->nullable();
			$table->text('content', 65535)->nullable();
			$table->integer('order')->default(0);
			$table->boolean('visible')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('push_notification_messages');
	}

}
