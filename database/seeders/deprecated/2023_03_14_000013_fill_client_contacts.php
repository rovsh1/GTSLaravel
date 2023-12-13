<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up()
    {
        $q = DB::connection('mysql_old')->table('client_contacts');

        foreach ($q->cursor() as $r) {
            try {
                DB::table('client_contacts')->insert([
                    'id' => $r->id,
                    'client_id' => $r->client_id,
                    'type' => $r->type,
                    'value' => $r->value,
                    'description' => $r->description,
                    'is_main' => (bool)$r->main,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {
                //@todo hack пропускаем клиентов с ошибками. На тесте какая то проблема с инсертом из-за городов
            }
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE client_contacts');
    }
};
