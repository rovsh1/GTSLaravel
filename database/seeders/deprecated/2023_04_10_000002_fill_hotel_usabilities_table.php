<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        $t = DB::connection('mysql_old')->getDatabaseName() . '.hotel_usabilities';
        DB::unprepared(
            'INSERT INTO hotel_usabilities (hotel_id, room_id,usability_id)'
            . ' SELECT hotel_id, room_id,usability_id'
            . ' FROM ' . $t
            . ' WHERE ' . $t . '.hotel_id IN (SELECT id FROM hotels)'
        );
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE hotel_usabilities');
    }
};
