<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBuildingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('buildings', function(Blueprint $table)
		{
			$table->foreign('campus_id', 'buildings_campus_id_foreign')->references('id')->on('campuses')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('school_id', 'buildings_school_id_foreign')->references('id')->on('schools')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('buildings', function(Blueprint $table)
		{
			$table->dropForeign('buildings_campus_id_foreign');
			$table->dropForeign('buildings_school_id_foreign');
		});
	}

}
