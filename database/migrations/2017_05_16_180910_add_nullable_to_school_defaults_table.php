<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableToSchoolDefaultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('school_defaults', function(Blueprint $table)
		{
			$table->string('sms_provider')->nullable()->change();
			$table->string('phone_system_provider')->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('school_defaults', function(Blueprint $table)
		{
			$table->string('sms_provider')->nullable(false)->change();
			$table->string('phone_system_provider')->nullable(false)->change();
		});
	}
}
