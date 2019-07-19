<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToDevicesTableTacPhone extends Migration {

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
      $table->string('user_phone_token', 255)->nullable();
      $table->boolean('user_phone_confirm')->default(false);
      $table->boolean('need_phone')->default(false);
      $table->boolean('need_tac')->default(true);
      $table->boolean('renew')->default(false);

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
      $table->dropColumn(['user_phone_confirm']);
			$table->dropColumn(['user_phone_token']);
			$table->dropColumn(['need_phone']);
			$table->dropColumn(['need_tac']);
			$table->dropColumn(['renew']);
		});
	}

}
