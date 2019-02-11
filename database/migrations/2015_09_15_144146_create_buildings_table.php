<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBuildingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('buildings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id')->unsigned()->default(0)->index('school_id');
			$table->integer('campus_id')->unsigned()->default(0)->index('campus_id');
			$table->string('building_ale_id', 64)->default('0');
			$table->string('building_name', 64)->default('0');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('buildings');
	}

}
