<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSchoolsTable2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('schools', function(Blueprint $table)
		{
      $table->string('url', 255)->nullable();
      $table->string('police_number', 255)->nullable();
      $table->tinyInteger('use_gps', 1)->nullable();
      $table->tinyInteger('display', 4)->nullable();
      $table->tinyInteger('public', 1)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('schools', function(Blueprint $table)
		{
			$table->dropColumn(['url']);
			$table->dropColumn(['police_number']);
			$table->dropColumn(['use_gps']);
			$table->dropColumn(['display']);
			$table->dropColumn(['public']);
		});
	}

}
