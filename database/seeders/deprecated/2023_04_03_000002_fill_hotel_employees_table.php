<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(
            'INSERT INTO hotel_employees (id,hotel_id,fullname,department,post,created_at,updated_at)'
            . ' SELECT id,hotel_id,fullname,department,post,created,NOW()'
            . ' FROM ' . DB::connection('mysql_old')->getDatabaseName() . '.hotel_employees'
        );
    }

    public function down(): void
    {
        //
    }
};
