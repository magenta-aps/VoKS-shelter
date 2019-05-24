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
            $table->dateTime('triggered_at')->default('0000-00-00 00:00:00')->index('event_log_triggered_at');
			$table->timestamps();
		});

		DB::unprepared('
		    CREATE TRIGGER `copy_alarm_timestamp` BEFORE INSERT ON `event_logs`
		    FOR EACH ROW SET NEW.triggered_at = (SELECT triggered_at FROM school_statuses WHERE NEW.school_id = school_statuses.school_id);');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    DB::unprepared('DROP TRIGGER `copy_alarm_timestamp`');
		Schema::drop('event_logs');
	}

}
