<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private static array $migrationAssoc = [
        'bed-type' => 3,
//        'room-name' => 2,
        'room-type' => 1,
        'hotel-type' => 5,
        'administrator-post' => 11,
//        'client-legal-requisite' => 7,
        'client-legal-industry' => 10,
        'hotel-usability-group' => 19
    ];

    public function up()
    {
        foreach (self::$migrationAssoc as $key => $id) {
            $flag = DB::table('r_enums')
                ->where('group', $key)
                ->exists();
            if ($flag) {
                continue;
            }

            $q = DB::connection('mysql_old')->table('r_enums')
                ->addSelect('r_enums.*')
                ->addSelect(DB::raw('(SELECT name FROM r_enums_translation WHERE translatable_id=r_enums.id AND language="ru") as name'))
                ->where('group_id', $id);
            foreach ($q->cursor() as $r) {
                Db::table('r_enums')
                    ->insert([
                        'id' => $r->id,
                        'group' => $key
                    ]);

                Db::table('r_enums_translation')
                    ->insert([
                        'translatable_id' => $r->id,
                        'language' => 'ru',
                        'name' => $r->name
                    ]);
            }
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE r_enums_translation');
        DB::statement('TRUNCATE TABLE r_enums');
    }
};
