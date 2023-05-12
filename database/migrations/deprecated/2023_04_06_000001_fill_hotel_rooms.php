<?php

use Illuminate\Database\Migrations\Migration;
use Module\Hotel\Domain\Entity\Room\MarkupSettings;
use Module\Shared\Domain\ValueObject\Percent;

return new class extends Migration {

    private const OTA = 1;
    private const TA = 2;
    private const TO = 3;
    private const INDIVIDUAL = 4;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('hotel_rooms')
            ->addSelect('hotel_rooms.*')
            ->addSelect(
                DB::raw(
                    '(SELECT text FROM hotel_rooms_translation WHERE translatable_id=hotel_rooms.id AND language="ru") as text'
                )
            );

        foreach ($q->cursor() as $r) {
            $margins = DB::connection('mysql_old')
                ->table('hotel_margins')
                ->where('room_id', $r->id)
                ->select([
                    \DB::raw(
                        "(SELECT `value` FROM hotel_margins WHERE hotel_id = {$r->hotel_id} AND room_id = {$r->id} AND client_model = " . self::OTA . ') as `OTA`'
                    ),
                    \DB::raw(
                        "(SELECT `value` FROM hotel_margins WHERE hotel_id = {$r->hotel_id} AND room_id = {$r->id} AND client_model = " . self::TA . ') as `TA`'
                    ),
                    \DB::raw(
                        "(SELECT `value` FROM hotel_margins WHERE hotel_id = {$r->hotel_id} AND room_id = {$r->id} AND client_model = " . self::TO . ') as `TO`'
                    ),
                    \DB::raw(
                        "(SELECT `value` FROM hotel_margins WHERE hotel_id = {$r->hotel_id} AND room_id = {$r->id} AND client_model = " . self::INDIVIDUAL . ') as `individual`'
                    ),
                ])
                ->first();

            $roomMarkup = new MarkupSettings(
                $r->id,
                new Percent($margins->individual ?? 0),
                new Percent($margins->TA ?? 0),
                new Percent($margins->OTA ?? 0),
                new Percent($margins->TO ?? 0),
                new Percent($room->price_discount ?? 0),
            );

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
                    'markup_settings' => json_encode($roomMarkup->toData())
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
