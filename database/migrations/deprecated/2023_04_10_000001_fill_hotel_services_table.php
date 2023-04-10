<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $q = DB::connection('mysql_old')->table('hotel_services');
        foreach ($q->cursor() as $r) {
            DB::table('hotel_services')
                ->insert([
                    'hotel_id' => $r->hotel_id,
                    'service_id' => $r->service_id,
                    'is_paid' => $r->pay,
                ]);
        }
    }

    public function down()
    {
        DB::statement('TRUNCATE TABLE hotel_services');
    }
};
