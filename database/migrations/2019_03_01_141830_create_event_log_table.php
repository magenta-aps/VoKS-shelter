<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('log_type')->default('Unspecified')->index('event_log_type');
            $table->integer('school_id')->unsigned()->nullable()->index('event_log_school_id');
            $table->string('device_type')->nullable()->default('');
            $table->string('device_id')->nullable()->default('')->index('event_log_device_id');
            $table->string('fullname')->nullable();
            $table->string('mac_address')->nullable()->default('');
            $table->string('floor_id')->nullable()->index('device_floor_id');
            $table->float('x', 10, 3)->nullable();
            $table->float('y', 10, 3)->nullable();
            $table->text('data')->nullable()->default('');
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
		Schema::drop('event_log');
	}

}
