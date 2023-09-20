<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        DB::unprepared(
            'INSERT INTO hotel_services (hotel_id, service_id,is_paid)'
            . ' SELECT hotel_id, service_id,pay'
            . ' FROM ' . DB::connection('mysql_old')->getDatabaseName() . '.hotel_services'
        );
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE hotel_services');
    }
};
