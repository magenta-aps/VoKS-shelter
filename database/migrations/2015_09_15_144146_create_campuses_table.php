<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCampusesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('campuses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id')->unsigned()->nullable()->index('school_id');
			$table->string('campus_ale_id', 64)->default('0');
			$table->string('campus_name', 64)->default('0');
			$table->string('campus_hash_id', 64);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('campuses');
	}

}