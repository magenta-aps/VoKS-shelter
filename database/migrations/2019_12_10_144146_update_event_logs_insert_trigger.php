<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventLogsInsertTrigger extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::unprepared('DROP TRIGGER IF EXISTS `copy_alarm_timestamp`'); // Ensure we drop previously migrated trigger
        DB::unprepared("
            CREATE TRIGGER `copy_alarm_timestamp`
            BEFORE INSERT
            ON `event_logs` FOR EACH ROW
            BEGIN
                IF (NEW.log_type = 'alarm_triggered')
                THEN
                    SET NEW.triggered_at = NEW.created_at;
                ELSE
                    SET NEW.triggered_at = (SELECT triggered_at FROM school_statuses WHERE NEW.school_id = school_statuses.school_id);
                END IF;
            END
        ");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::unprepared('DROP TRIGGER IF EXISTS `copy_alarm_timestamp`');
	}

}