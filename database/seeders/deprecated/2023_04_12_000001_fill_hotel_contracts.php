<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $t = DB::connection('mysql_old')->getDatabaseName() . '.hotel_contracts';
        DB::unprepared(
            'INSERT INTO hotel_contracts (id,hotel_id,status,date_start,date_end,created_at,updated_at)'
            . ' SELECT id,hotel_id,status,date_from,date_to,created,NOW()'
            . ' FROM ' . $t
            . ' WHERE ' . $t . '.hotel_id IN (SELECT id FROM hotels)'
        );
    }

    public function down(): void
    {
        DB::table('hotel_contracts')->truncate();
    }
};
