<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventReports extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->dropView());
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    private function dropView() {
        return <<<SQL
DROP VIEW IF EXISTS `event_reports`;
SQL;
    }

    private function createView() {
        return <<<SQL
CREATE VIEW `event_reports` AS 
SELECT id, school_id, triggered_at, device_type, device_id, fullname, created_at, data,
sum(log_type = "push_notification") as push_notifications,
sum(log_type = "video_chat") as video_chats,
TIMESTAMPDIFF(SECOND, MIN(created_at), MAX(created_at)) as duration
FROM `event_logs` GROUP BY triggered_at, school_id;
SQL;
    }
}
