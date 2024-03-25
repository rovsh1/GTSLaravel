<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $newDbName = DB::getDatabaseName();
        $oldDbName = DB::connection('mysql_old')->getDatabaseName();
        DB::unprepared(
            'INSERT INTO users (id, client_id, country_id,presentation, gender, login, password,email,phone,post_id,address,note,status,role,birthday,created_at,updated_at)'
            . ' SELECT id, client_id, country_id,presentation, gender, login, password,email,phone,post_id,address,note,status,role,birthday,NOW(),NOW()'
            . ' FROM ' . $oldDbName . '.users'.
            ' WHERE presentation NOT LIKE "%http%" AND presentation NOT LIKE "%oпрoс%" AND presentation NOT LIKE "%зapабатывaть%"'.
            " AND EXISTS (SELECT 1 FROM $newDbName.clients WHERE $newDbName.clients.id = $oldDbName.users.client_id)"
        );
    }

    public function down(): void
    {
        DB::table('users')->truncate();
    }
};
