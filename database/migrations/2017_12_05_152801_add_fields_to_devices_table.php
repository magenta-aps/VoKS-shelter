<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('devices', function(Blueprint $table)
		{
			$table->string('user_email', 255)->nullable();
			$table->string('username', 255)->nullable();
			$table->string('role', 255)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('devices', function(Blueprint $table)
		{
			$table->dropColumn(['user_email', 'username', 'role']);
		});
	}

}
