<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCrisisTeamMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('crisis_team_members', function(Blueprint $table)
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
		Schema::table('crisis_team_members', function(Blueprint $table)
		{
			$table->dropForeign('crisis_team_members_school_id_foreign');
		});
	}

}
