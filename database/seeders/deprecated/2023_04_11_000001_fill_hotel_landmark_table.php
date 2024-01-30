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
        $t = DB::connection('mysql_old')->getDatabaseName() . '.hotel_showplace';
        DB::unprepared(
            'INSERT INTO hotel_landmark (hotel_id, landmark_id,distance)'
            . ' SELECT hotel_id, showplace_id,distance'
            . ' FROM ' . $t
            . ' WHERE ' . $t . '.hotel_id IN (SELECT id FROM hotels)'
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
