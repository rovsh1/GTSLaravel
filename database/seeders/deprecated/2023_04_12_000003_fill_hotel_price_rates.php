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
        $q = DB::connection('mysql_old')->table('hotel_price_rates')
            ->addSelect('hotel_price_rates.*')
            ->join('hotel_price_rates_translation','hotel_price_rates_translation.translatable_id','=','hotel_price_rates.id')
            ->addSelect('hotel_price_rates_translation.name as name')
            ->addSelect('hotel_price_rates_translation.text as description')
            ->get();

        foreach ($q as $r) {
            DB::table('hotel_price_rates')
                ->insert([
                    'id' => $r->id,
                    'hotel_id' => $r->hotel_id,
                ]);

            DB::table('hotel_price_rates_translation')
                ->insert([
                    'translatable_id' => $r->id,
                    'language' => 'ru',
                    'name' => $r->name,
                    'description' => $r->description
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hotel_price_rates_translation')->truncate();
        DB::table('hotel_price_rates')->truncate();
    }
};
