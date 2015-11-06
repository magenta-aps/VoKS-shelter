<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFaqDefaultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faq_defaults', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('question', 65535);
			$table->text('answer', 65535);
			$table->integer('order');
			$table->boolean('visible');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('faq_defaults');
	}

}
