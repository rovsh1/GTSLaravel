<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared(
            'INSERT INTO hotel_contacts (id, hotel_id, employee_id,type, value, description, is_main, created_at, updated_at)'
            . ' SELECT id, hotel_id, employee_id,type, value, description, main, NOW(), NOW()'
            . ' FROM ' . DB::connection('mysql_old')->getDatabaseName() . '.hotel_contacts'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
