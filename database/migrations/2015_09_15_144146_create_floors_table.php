<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFloorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('floors', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id')->unsigned()->index('school_id');
			$table->integer('building_id')->unsigned()->default(0)->index('building_id');
			$table->integer('floor_image_id')->nullable()->default(0);
			$table->string('floor_ale_id', 50)->nullable()->default('0');
			$table->string('floor_name', 50)->nullable()->default('0');
			$table->integer('floor_number');
			$table->string('floor_hash_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('floors');
	}

}
