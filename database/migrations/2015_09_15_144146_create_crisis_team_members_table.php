<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCrisisTeamMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('crisis_team_members', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id')->unsigned()->default(0)->index('school_id');
			$table->string('name');
			$table->string('email');
			$table->string('phone');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('crisis_team_members');
	}

}
