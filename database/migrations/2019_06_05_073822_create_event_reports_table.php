<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('event_reports', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('school_id')->unsigned()->nullable()->index('event_log_school_id');
            $table->integer('event_log_trigger_id')->unsigned()->nullable();
            $table->boolean('false_alarm')->default(0);
            $table->text('note')->nullable();
            $table->string('video_download_link')->nullable();
            $table->dateTime('triggered_at')->default('0000-00-00 00:00:00');
            $table->timestamps();
        });

        DB::unprepared('
        CREATE TRIGGER `copy_alarm_data_to_reports` AFTER INSERT ON `event_logs`
		    FOR EACH ROW IF (NEW.log_type = "alarm_triggered") THEN
                INSERT INTO event_reports values (NULL, NEW.school_id, NEW.id, 0, NULL, NULL, NEW.triggered_at, NOW(), NOW());
            END IF;
        ');


    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    DB::unprepared('DROP TRIGGER `copy_alarm_data_to_reports`');
		Schema::drop('event_reports');
	}

}
