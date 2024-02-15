<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared(
            'INSERT INTO hotel_landmark (hotel_id, landmark_id,distance)'
            . ' SELECT hotel_id, showplace_id,distance'
            . ' FROM ' . DB::connection('mysql_old')->getDatabaseName() . '.hotel_showplace'
            . ' WHERE EXISTS(SELECT 1 FROM ' . DB::connection()->getDatabaseName() . '.hotels WHERE hotel_id = hotels.id)'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hotel_landmark')->truncate();
    }
};
