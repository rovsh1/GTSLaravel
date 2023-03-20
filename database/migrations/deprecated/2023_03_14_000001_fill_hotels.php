<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $q = DB::connection('mysql_old')->table('hotels');
        foreach ($q->cursor() as $r) {
            DB::table('hotels')
                ->insert([
                    'id' => $r->id,
                    'city_id' => $r->city_id,
                    'type_id' => $r->type_id,
                    'rating' => $r->rating,
                    'name' => $r->name,
                    'address' => $r->address,
                    'address_lat' => $r->latitude,
                    'address_lon' => $r->longitude,
                    'city_distance' => $r->citycenter_distance,
                    'zipcode' => $r->zipcode,
                    'status' => $r->status,
                    'visibility' => $r->visible_for ?? 0,
                    'created_at' => $r->created,
                    'updated_at' => $r->updated,
                ]);
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE hotels');
    }
};
