<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $q = DB::connection('mysql_old')->table('clients');
        foreach ($q->cursor() as $r) {
            DB::table('clients')
                ->insert([
                    'id' => $r->id,
                    'city_id' => $r->city_id,
                    'currency_id' => $r->currency_id,
                    'type' => $r->type,
                    'name' => $r->name,
                    'description' => $r->description,
                    'status' => $r->status,
                    'created_at' => $r->created,
                    'updated_at' => $r->updated
                ]);
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE clients');
    }
};
