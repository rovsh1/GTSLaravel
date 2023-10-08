<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(
            'INSERT INTO users (id, client_id, country_id,name, surname, patronymic,presentation, gender, login, password,email,phone,post_id,address,note,status,role,birthday,created_at,updated_at)'
            . ' SELECT id, client_id, country_id,name, surname, patronymic,presentation, gender, login, password,email,phone,post_id,address,note,status,role,birthday,NOW(),NOW()'
            . ' FROM ' . DB::connection('mysql_old')->getDatabaseName() . '.users'
        );
    }

    public function down(): void
    {
        DB::table('users')->truncate();
    }
};
