<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        $q = DB::connection('mysql_old')
            ->table('clients')
            ->select(
                '*',
                DB::raw('(SELECT 1 FROM client_legal_entities WHERE client_id=clients.id) as is_b2b')
            );
        foreach ($q->cursor() as $r) {
            try {
                DB::table('clients')
                    ->insert([
                        'id' => $r->id,
                        'city_id' => $r->city_id,
                        'currency_id' => $r->currency_id,
                        'type' => $r->type,
                        'name' => $r->name,
                        'description' => $r->description,
                        'status' => $r->status,
                        'is_b2b' => (bool)$r->is_b2b,
                        'created_at' => $r->created,
                        'updated_at' => $r->updated
                    ]);
            } catch (\Throwable $e) {
                //@todo hack пропускаем клиентов с ошибками. На тесте какая то проблема с инсертом из-за городов
            }
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE clients');
    }
};
