<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFloorImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('floor_images', function(Blueprint $table)
		{
			$table->foreign('floor_id')->references('id')->on('floors')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('floor_images', function(Blueprint $table)
		{
			$table->dropForeign('floor_images_floor_id_foreign');
		});
	}

}
