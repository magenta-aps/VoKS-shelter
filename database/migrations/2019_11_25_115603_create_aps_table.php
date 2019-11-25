<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
	{
		Schema::create('aps', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id');
			$table->integer('floor_id');
            $table->string('ap_ale_id', 50);
            $table->string('ap_ale_name', 255);
            $table->string('mac_address', 255);
			$table->float('x', 10, 3);
			$table->float('y', 10, 3);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('aps');
	}
}
