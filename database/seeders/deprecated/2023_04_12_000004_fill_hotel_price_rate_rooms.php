<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('hotel_price_rate_rooms');
        foreach ($q->cursor() as $r) {
            try {
                DB::table('hotel_price_rate_rooms')
                    ->insert([
                        'rate_id' => $r->rate_id,
                        'room_id' => $r->room_id,
                    ]);
            } catch (\Throwable $e) {
                //@todo локально были записи где удален rate_id
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hotel_price_rate_rooms')->truncate();
    }
};
