<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        $t = DB::connection('mysql_old')->getDatabaseName() . '.hotel_services';
        DB::unprepared(
            'INSERT INTO hotel_services (hotel_id, service_id,is_paid)'
            . ' SELECT hotel_id, service_id,pay'
            . ' FROM ' . $t
            . ' WHERE ' . $t . '.hotel_id IN (SELECT id FROM hotels)'
        );
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE hotel_services');
    }
};
