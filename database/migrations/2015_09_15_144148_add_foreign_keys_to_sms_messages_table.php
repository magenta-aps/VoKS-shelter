<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSmsMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sms_messages', function(Blueprint $table)
		{
			$table->foreign('school_id', 'sms_messages_school_id_foreign')->references('id')->on('schools')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sms_messages', function(Blueprint $table)
		{
			$table->dropForeign('sms_messages_school_id_foreign');
		});
	}

}
