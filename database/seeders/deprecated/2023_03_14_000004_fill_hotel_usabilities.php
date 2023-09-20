<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $q = DB::connection('mysql_old')->table('r_usabilities')
            ->addSelect('r_usabilities.*')
            ->addSelect(DB::raw('(SELECT name FROM r_usabilities_translation WHERE translatable_id=r_usabilities.id AND language="ru") as name'))
            ->get();
        foreach ($q as $r) {
            DB::table('r_hotel_usabilities')
                ->insert([
                    'id' => $r->id,
                    'group_id' => $r->group_id,
                    'popular' => $r->popular
                ]);

            DB::table('r_hotel_usabilities_translation')
                ->insert([
                    'translatable_id' => $r->id,
                    'language' => 'ru',
                    'name' => $r->name
                ]);
        }
    }

    public function down() {}
};
