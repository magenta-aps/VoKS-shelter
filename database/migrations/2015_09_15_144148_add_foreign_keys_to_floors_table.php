<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFloorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('floors', function(Blueprint $table)
		{
			$table->foreign('school_id')->references('id')->on('schools')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('building_id')->references('id')->on('buildings')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('floors', function(Blueprint $table)
		{
			$table->dropForeign('floors_school_id_foreign');
			$table->dropForeign('floors_building_id_foreign');
		});
	}

}
