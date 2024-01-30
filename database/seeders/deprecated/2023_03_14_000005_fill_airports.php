<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        $q = DB::connection('mysql_old')
            ->table('r_airports')
            ->whereNot('name', 'like', '%test%');
        foreach ($q->cursor() as $r) {
            DB::table('r_airports')
                ->insert([
                    'id' => $r->id,
                    'city_id' => $r->city_id,
                    'name' => $r->name,
                    'code' => $r->code,
                ]);
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE r_airports');
    }
};
