<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToDevicesTable4 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('devices', function(Blueprint $table)
		{
      $table->string('user_phone', 255)->nullable();
      $table->boolean('need_phone')->default(true);
      $table->boolean('need_tac')->default(true);
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
			$table->dropColumn(['user_phone']);
			$table->dropColumn(['need_phone']);
			$table->dropColumn(['need_tac']);
		});
	}

}
