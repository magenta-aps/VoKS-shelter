<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHelpFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('help_files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id')->unsigned()->index('school_id');
			$table->text('description');
			$table->string('type', 5);
			$table->string('file_path')->nullable();
			$table->string('file_url')->nullable();
			$table->string('police_name')->nullable();
			$table->string('police_file_path')->nullable();
			$table->string('name')->nullable();
			$table->string('police_file_url')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('help_files');
	}

}
