<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFaqsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('faqs', function(Blueprint $table)
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
		Schema::table('faqs', function(Blueprint $table)
		{
			$table->dropForeign('faqs_school_id_foreign');
		});
	}

}
