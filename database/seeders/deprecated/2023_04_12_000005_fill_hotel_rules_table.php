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
        $q = DB::connection('mysql_old')->table('hotel_rules')
            ->addSelect('hotel_rules.*')
            ->join('hotel_rules_translation','hotel_rules_translation.translatable_id','=','hotel_rules.id')
            ->addSelect('hotel_rules_translation.name as name')
            ->addSelect('hotel_rules_translation.text as text')
            ->get();

        foreach ($q as $r) {
            DB::table('hotel_rules')
                ->insert([
                    'id' => $r->id,
                    'hotel_id' => $r->hotel_id,
                ]);

            DB::table('hotel_rules_translation')
                ->insert([
                    'translatable_id' => $r->id,
                    'language' => 'ru',
                    'name' => $r->name,
                    'text' => $r->text
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hotel_rules_translation')->truncate();
        DB::table('hotel_rules')->truncate();
    }
};
