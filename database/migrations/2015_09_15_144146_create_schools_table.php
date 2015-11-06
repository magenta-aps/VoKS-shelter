<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSchoolsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schools', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('campus_id')->unsigned();
			$table->string('name', 256);
			$table->string('locale', 3);
			$table->string('mac_address')->nullable();
			$table->string('ip_address')->nullable();
			$table->string('timezone')->nullable();
			$table->string('ordering')->nullable();
			$table->string('ad_id');
			$table->string('phone_system_id')->nullable();
			$table->integer('phone_system_group_id')->nullable();
			$table->integer('phone_system_voice_id')->nullable();
			$table->string('phone_system_number')->nullable();
			$table->string('phone_system_interrupt_id')->nullable();
		});

		// should this be configurable?
		\DB::statement("ALTER TABLE `schools` CHANGE COLUMN `name` `name` VARCHAR(256) NOT NULL COLLATE '".config('database.school_name_collation', 'utf8_general_ci')."' AFTER `id`");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('schools');
	}

}
