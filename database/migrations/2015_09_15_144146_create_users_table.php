<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('ad_user_id', 45)->nullable();
			$table->integer('school_id')->unsigned()->nullable();
			$table->string('username', 64)->nullable();
			$table->string('role', 20)->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->string('password', 60)->nullable();
			$table->string('email', 45)->nullable();
			$table->string('name', 45)->nullable();
			$table->string('surname', 45)->nullable();
			$table->string('fullname', 90)->nullable();
			$table->string('phone', 45)->nullable();
			$table->string('position', 45)->nullable();
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
		Schema::drop('users');
	}

}
