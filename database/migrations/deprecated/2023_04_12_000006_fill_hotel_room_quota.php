<?php

use Illuminate\Database\Migrations\Migration;
use Module\Hotel\Infrastructure\Models\Room\QuotaStatusEnum;

return new class extends Migration {

    private const CLOSE_VALUE_DEPRECATED = 2;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $q = DB::connection('mysql_old')->table('hotel_room_quotes');
        foreach ($q->cursor() as $r) {
            $countAvailable = $r->count_available - $r->count_booked;
            if ($countAvailable < 0) {
                //@hack на проде есть данные где отрицательные available
                $countAvailable = 0;
            }
            DB::table('hotel_room_quota')
                ->insert([
                    'room_id' => $r->room_id,
                    'date' => $r->date,
                    'release_days' => $r->period,
                    'count_total' => $r->count_available,
//                    'count_available' => $countAvailable,
//                    'count_booked' => $r->count_booked,
                    //@todo count_reserved нужно смотреть как считается
//                    'count_reserved' => 0,
                    'status' => $r->type == self::CLOSE_VALUE_DEPRECATED ? QuotaStatusEnum::CLOSE : QuotaStatusEnum::OPEN,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hotel_room_quota')->truncate();
    }
};
