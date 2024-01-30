<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('hotel_seasons');
        foreach ($q->cursor() as $r) {
            if (!DB::table('hotels')->where('id', $r->hotel_id)->exists()) {
                continue;
            }
            try {
                DB::table('hotel_seasons')
                    ->insert([
                        'id' => $r->id,
                        'contract_id' => $r->contract_id,
                        'name' => $r->name,
                        'date_start' => $r->date_from,
                        'date_end' => $r->date_to,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            } catch (\Throwable $e) {
                //@todo локально были сезоны без договоров
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hotel_seasons')->truncate();
    }
};
