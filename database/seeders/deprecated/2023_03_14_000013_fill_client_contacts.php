<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up()
    {
        $q = DB::connection('mysql_old')->table('client_contacts')
            ->whereNot('value', 'like', '%тест%')
            ->whereNot('value', 'like', '%test%');

        foreach ($q->cursor() as $r) {
            if (!DB::table('clients')->where('id', $r->client_id)->exists()) {
                continue;
            }
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
        if (app()->environment('prod', 'production')) {
            return;
        }
        //@hack для тестов Бахтиера (с прода почему-то не переносится)
//        DB::table('client_contacts')->insert([
//            'client_id' => 6,
//            'type' => \Sdk\Shared\Enum\ContactTypeEnum::EMAIL->value,
//            'value' => 'testust2354@mail.ru',
//            'description' => 'testust2354@mail.ru',
//            'is_main' => 1,
//            'created_at' => now(),
//            'updated_at' => now(),
//        ]);
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE client_contacts');
    }
};
