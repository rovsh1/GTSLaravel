<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $q = DB::connection('mysql_old')->table('r_showplace_types');
        foreach ($q->cursor() as $r) {
            DB::table('r_landmark_types')
                ->insert([
                    'id' => $r->id,
                    'alias' => $r->alias,
                    'name' => $r->name,
                    //'city' => $r->roundcity,
                ]);
        }

        $q = DB::connection('mysql_old')->table('r_showplaces');
        foreach ($q->cursor() as $r) {
            DB::table('r_landmarks')
                ->insert([
                    'id' => $r->id,
                    'type_id' => $r->type_id > 0 ? $r->type_id : null,
                    'city_id' => $r->city_id,
                    'address' => $r->address,
                    'address_lat' => $r->latitude,
                    'address_lon' => $r->longitude,
                    'city_distance' => $r->distance,
                    //'city' => $r->roundcity,
                ]);

            Db::table('r_landmarks_translation')
                ->insert([
                    'translatable_id' => $r->id,
                    'language' => 'ru',
                    'name' => $r->name
                ]);
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE r_landmarks');
        DB::statement('TRUNCATE TABLE r_landmark_types');
    }
};
