<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobs', function(Blueprint $table)
		{
			$table->index('queue');
			$table->index('reserved');
			$table->index('available_at');
			$table->index('reserved_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jobs', function(Blueprint $table)
		{
			$table->dropIndex('jobs_queue_index');
			$table->dropIndex('jobs_reserved_index');
			$table->dropIndex('jobs_available_at_index');
			$table->dropIndex('jobs_reserved_at_index');
		});
	}

}
