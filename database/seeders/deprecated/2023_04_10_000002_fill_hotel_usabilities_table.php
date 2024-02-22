<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        DB::unprepared(
            'INSERT INTO hotel_usabilities (hotel_id, room_id,usability_id)'
            . ' SELECT hotel_id, room_id,usability_id'
            . ' FROM ' . DB::connection('mysql_old')->getDatabaseName() . '.hotel_usabilities'
            . ' WHERE EXISTS(SELECT 1 FROM ' . DB::connection()->getDatabaseName() . '.hotels WHERE hotel_id = hotels.id)'
        );
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE hotel_usabilities');
    }
};
