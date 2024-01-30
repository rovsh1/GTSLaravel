<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
//        DB::unprepared(
//            'INSERT INTO users (id, client_id, country_id,name, surname, patronymic,presentation, gender, login, password,email,phone,post_id,address,note,status,role,birthday,created_at,updated_at)'
//            . ' SELECT id, client_id, country_id,name, surname, patronymic,presentation, gender, login, password,email,phone,post_id,address,note,status,role,birthday,NOW(),NOW()'
//            . ' FROM ' . DB::connection('mysql_old')->getDatabaseName() . '.users' .
//            ' WHERE presentation NOT LIKE "%http%"'
//            . ' AND presentation NOT LIKE "%oпрoс%"'
//            . ' AND presentation NOT LIKE "%test%"'
//            . ' AND presentation NOT LIKE "%тест%"'
//            . ' AND presentation NOT LIKE "%fio%"'
//            . ' AND presentation NOT LIKE "%зapабатывaть%"'
//            . ' AND client_id IN (SELECT id FROM clients)'
//            . ' AND id NOT IN (1, 11, 13, 21, 24)'
//        );
    }

    public function down(): void
    {
        DB::table('users')->truncate();
    }
};
