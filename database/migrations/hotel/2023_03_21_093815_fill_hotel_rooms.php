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
        $q = DB::connection('mysql_old')->table('hotel_rooms')
            ->addSelect('hotel_rooms.*')
            ->addSelect(DB::raw('(SELECT text FROM hotel_rooms_translation WHERE translatable_id=hotel_rooms.id AND language="ru") as text'))
            ->addSelect(DB::raw('(SELECT name FROM r_enums_translation WHERE translatable_id=hotel_rooms.name_id AND language="ru") as name'));

        foreach ($q->cursor() as $r) {
            DB::table('hotel_rooms')
                ->insert(
                    \Arr::except(get_object_vars($r), ['name_id', 'name', 'text'])
                );

            DB::table('hotel_rooms_translation')
                ->insert([
                    'translatable_id' => $r->id,
                    'language' => 'ru',
                    'name' => $r->name,
                    'text' => $r->text,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
