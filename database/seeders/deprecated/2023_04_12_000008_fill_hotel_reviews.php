<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')
            ->table('hotel_reviews')
            ->whereExists(function (\Illuminate\Database\Query\Builder $query){
                $query->selectRaw(1)
                    ->from(\DB::connection()->getDatabaseName() . '.hotels')
                    ->whereColumn('hotel_id', 'hotels.id');
            })
            ->cursor();
        foreach ($q as $r) {
            DB::table('hotel_reviews')
                ->insert([
                    'id' => $r->id,
                    'hotel_id' => $r->hotel_id,
                    'booking_id' => null,
                    'name' => $r->name,
                    'text' => $r->text,
                    'rating' => $r->rating,
                    'status' => $r->status,
                    'created_at' => $r->created,
                    'updated_at' => $r->created,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hotel_reviews')->truncate();
    }
};
