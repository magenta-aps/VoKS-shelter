<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCampusesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('campuses', function(Blueprint $table)
		{
			$table->foreign('school_id')->references('id')->on('schools')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('campuses', function(Blueprint $table)
		{
			$table->dropForeign('campuses_school_id_foreign');
		});
	}

}
