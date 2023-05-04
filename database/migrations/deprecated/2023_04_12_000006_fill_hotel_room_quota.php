<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('hotel_room_quotes');
        foreach ($q->cursor() as $r) {
            DB::table('hotel_room_quota')
                ->insert([
                    'room_id' => $r->room_id,
                    'date' => $r->date,
                    'release_days' => $r->period,
                    'count_total' => $r->count_available,
                    'count_available' => $r->count_available - $r->count_booked,
                    'count_booked' => $r->count_booked,
                    //@todo count_reserved нужно смотреть как считается
                    'count_reserved' => 0,
                    'status' => $r->type,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hotel_room_quota')->truncate();
    }
};
