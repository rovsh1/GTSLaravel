<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('hotel_rooms')
            ->addSelect('hotel_rooms.*')
            ->addSelect(DB::raw('(SELECT text FROM hotel_rooms_translation WHERE translatable_id=hotel_rooms.id AND language="ru") as text'));

        foreach ($q->cursor() as $r) {
            DB::table('hotel_rooms')
                ->insert([
                    'id' => $r->id,
                    'hotel_id' => $r->hotel_id,
                    'name_id' => $r->name_id,
                    'type_id' => $r->type_id,
                    'custom_name' => $r->custom_name,
                    'rooms_number' => $r->rooms_number,
                    'guests_number' => $r->guests_number,
                    'square' => $r->size,
                    'position' => $r->index,
                ]);

            if (!empty($r->text)) {
                DB::table('hotel_rooms_translation')
                    ->insert([
                        'translatable_id' => $r->id,
                        'language' => 'ru',
                        'text' => $r->text,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hotel_rooms')->truncate();
        DB::table('hotel_rooms_translation')->truncate();
    }
};
