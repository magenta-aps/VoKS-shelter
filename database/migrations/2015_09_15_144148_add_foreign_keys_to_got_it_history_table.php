<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGotItHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('got_it_history', function(Blueprint $table)
		{
			$table->foreign('notification_id')->references('id')->on('sent_push_notifications')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('school_id')->references('id')->on('schools')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('device_id')->references('id')->on('devices')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('got_it_history', function(Blueprint $table)
		{
			$table->dropForeign('got_it_history_notification_id_foreign');
			$table->dropForeign('got_it_history_school_id_foreign');
			$table->dropForeign('got_it_history_device_id_foreign');
		});
	}

}
