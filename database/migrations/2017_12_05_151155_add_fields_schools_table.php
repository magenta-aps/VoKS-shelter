<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsSchoolsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('schools', function(Blueprint $table)
		{
          $table->string('url', 255);
          $table->string('police_number', 255);
          $table->tinyInteger('use_gps', false);
          $table->tinyInteger('display', false);
          $table->tinyInteger('public', false);
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
			$table->dropColumn(['url', 'police_number', 'use_gps', 'display', 'public']);
		});
	}

}
