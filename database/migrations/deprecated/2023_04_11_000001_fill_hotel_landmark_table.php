<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('hotel_showplace');
        foreach ($q->cursor() as $r) {
            DB::table('hotel_landmark')
                ->insert([
                    'hotel_id' => $r->hotel_id,
                    'landmark_id' => $r->showplace_id,
                    'distance' => (int)$r->distance,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('hotel_landmark')->truncate();
    }
};
