<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateDevicesTable
 */
class CreateDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('devices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id')->unsigned()->nullable()->index('school_id');
			$table->string('device_type')->nullable()->default('');
			$table->string('device_id')->nullable()->default('')->index('device_device_id');
			$table->string('fullname')->nullable();
			$table->string('mac_address')->default('')->unique();
			$table->string('push_notification_id')->nullable()->default('');
			$table->integer('trigger_status')->nullable();
			$table->string('triggered_at', 45)->nullable();
			$table->string('floor_id')->nullable()->index('device_floor_id');
			$table->float('x', 10, 3)->default(0);
			$table->float('y', 10, 3)->default(0);
			$table->boolean('active')->default(false);
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
		Schema::drop('devices');
	}

}
