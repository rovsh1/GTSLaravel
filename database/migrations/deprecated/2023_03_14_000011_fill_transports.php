<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $q = DB::connection('mysql_old')->table('r_transport_types')
            ->addSelect('r_transport_types.*')
            ->addSelect(DB::raw('(SELECT name FROM r_transport_types_translation WHERE translatable_id=r_transport_types.id AND language="ru") as name'));
        foreach ($q->cursor() as $r) {
            DB::table('r_transport_types')
                ->insert([
                    'id' => $r->id,
                    'color' => $r->color
                ]);

            Db::table('r_transport_types_translation')
                ->insert([
                    'translatable_id' => $r->id,
                    'language' => 'ru',
                    'name' => $r->name
                ]);
        }

        $q = DB::connection('mysql_old')->table('r_cars');
        foreach ($q->cursor() as $r) {
            DB::table('r_transport_cars')
                ->insert([
                    'id' => $r->id,
                    'type_id' => $r->type_id,
                    'mark' => $r->mark,
                    'model' => $r->model,
                    'passengers_number' => $r->passengers_number,
                    'bags_number' => $r->bags_number,
                ]);
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE r_transport_cars');
        DB::statement('TRUNCATE TABLE r_transport_types_translation');
        DB::statement('TRUNCATE TABLE r_transport_types');
    }
};
