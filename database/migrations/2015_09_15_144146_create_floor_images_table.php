<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFloorImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('floor_images', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('floor_id')->unsigned()->index('floor_id');
			$table->string('name')->nullable();
			$table->string('file_name')->nullable();
			$table->boolean('max_zoom_level')->nullable();
			$table->string('path')->nullable();
			$table->integer('pixel_width');
			$table->integer('pixel_height');
			$table->float('real_width', 10, 3)->default(0.000);
			$table->float('real_height', 10, 3)->default(0.000);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('floor_images');
	}

}
