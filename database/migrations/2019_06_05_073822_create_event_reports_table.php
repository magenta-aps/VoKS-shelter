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
            $table->string('log_type')->default('Unspecified')->index('event_log_type');
            $table->integer('school_id')->unsigned()->nullable()->index('event_log_school_id');
            $table->boolean('false_alarm')->default(0);
            $table->string('note')->nullable();
            $table->dateTime('triggered_at')->default('0000-00-00 00:00:00');
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
		Schema::drop('event_reports');
	}

}
