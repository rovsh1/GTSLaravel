<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('hotel_contracts');
        foreach ($q->cursor() as $r) {
            DB::table('hotel_contracts')
                ->insert([
                    'id' => $r->id,
                    'hotel_id' => $r->hotel_id,
                    'status' => $r->status,
                    'date_start' => $r->date_from,
                    'date_end' => $r->date_to,
                    'created_at' => $r->created,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hotel_contracts')->truncate();
    }
};
