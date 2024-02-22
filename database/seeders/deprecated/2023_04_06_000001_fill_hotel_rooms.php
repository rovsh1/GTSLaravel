<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Moderation\Domain\Hotel\Entity\Room\RoomMarkups;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\RoomId;
use Sdk\Shared\ValueObject\Percent;

return new class extends Migration {

    private const ROOM_NAME_ENUM_GROUP_ID = 2;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('hotel_rooms')
            ->addSelect('hotel_rooms.*')
            ->whereExists(function (\Illuminate\Database\Query\Builder $query) {
                $query->selectRaw(1)
                    ->from(\DB::connection()->getDatabaseName() . '.hotels')
                    ->whereColumn('hotel_id', 'hotels.id');
            })
            ->addSelect(
                DB::raw(
                    '(SELECT text FROM hotel_rooms_translation WHERE translatable_id=hotel_rooms.id AND language="ru") as text'
                )
            )
            ->leftJoin('r_enums', function (\Illuminate\Database\Query\JoinClause $query) {
                $query->whereColumn('hotel_rooms.name_id', 'r_enums.id')
                    ->where('group_id', self::ROOM_NAME_ENUM_GROUP_ID)
                    ->limit(1);
            })
            ->addSelect(
                DB::raw(
                    '(SELECT name FROM r_enums_translation WHERE r_enums_translation.translatable_id=r_enums.id AND language="ru") as room_name'
                )
            );

        foreach ($q->cursor() as $r) {
            $roomMarkup = new RoomMarkups(
                new RoomId($r->id),
                new Percent($room->price_discount ?? 0),
            );

            DB::table('hotel_rooms')
                ->insert([
                    'id' => $r->id,
                    'hotel_id' => $r->hotel_id,
                    'type_id' => $r->type_id,
                    'rooms_number' => $r->rooms_number,
                    'guests_count' => $r->guests_number,
                    'square' => $r->size,
                    'position' => $r->index,
                    'markup_settings' => json_encode($roomMarkup->serialize()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            $translations = [
                ['translatable_id' => $r->id, 'language' => 'ru', 'name' => $r->room_name, 'text' => $r->text ?? null],
                ['translatable_id' => $r->id, 'language' => 'en', 'name' => $r->custom_name, 'text' => null],
                ['translatable_id' => $r->id, 'language' => 'uz', 'name' => $r->custom_name, 'text' => null],
            ];
            DB::table('hotel_rooms_translation')->insert($translations);
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
