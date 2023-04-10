<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $q = DB::connection('mysql_old')->table('hotel_usabilities');
        foreach ($q->cursor() as $r) {
            DB::table('hotel_usabilities')
                ->insert([
                    'hotel_id' => $r->hotel_id,
                    'room_id' => $r->room_id,
                    'usability_id' => $r->usability_id,
                ]);
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE hotel_usabilities');
    }
};
