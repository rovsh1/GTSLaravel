<?php

use Illuminate\Database\Migrations\Migration;
use Module\Booking\Hotel\Application\UseCase\System\FillCalculatedPriceCalendar;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('hotel_calculated_price_calendar')->delete();
        DB::table('hotel_season_price_calendar')->delete();
        DB::table('hotel_season_prices')->delete();
        DB::table('hotel_price_groups')->delete();

        DB::table('hotel_price_groups')
            ->insert([
                ['id' => 1, 'rate_id' => 251, 'guests_count' => 1, 'is_resident' => 1],
                ['id' => 2, 'rate_id' => 251, 'guests_count' => 1, 'is_resident' => 0],
                ['id' => 3, 'rate_id' => 251, 'guests_count' => 2, 'is_resident' => 1],
                ['id' => 4, 'rate_id' => 251, 'guests_count' => 2, 'is_resident' => 0],
                ['id' => 5, 'rate_id' => 251, 'guests_count' => 3, 'is_resident' => 1],
                ['id' => 6, 'rate_id' => 251, 'guests_count' => 3, 'is_resident' => 0],
            ]);

        DB::table('hotel_season_prices')
            ->insert([
                ['season_id' => 1197, 'group_id' => 1, 'room_id' => 233, 'price' => 500000],
                ['season_id' => 1197, 'group_id' => 2, 'room_id' => 233, 'price' => 600000],
                ['season_id' => 1197, 'group_id' => 3, 'room_id' => 233, 'price' => 650000],
                ['season_id' => 1197, 'group_id' => 4, 'room_id' => 233, 'price' => 700000],

                ['season_id' => 1197, 'group_id' => 1, 'room_id' => 238, 'price' => 1000000],
                ['season_id' => 1197, 'group_id' => 2, 'room_id' => 238, 'price' => 2000000],
                ['season_id' => 1197, 'group_id' => 3, 'room_id' => 238, 'price' => 1000000],
                ['season_id' => 1197, 'group_id' => 4, 'room_id' => 238, 'price' => 2000000],
                ['season_id' => 1197, 'group_id' => 5, 'room_id' => 238, 'price' => 1000000],
                ['season_id' => 1197, 'group_id' => 6, 'room_id' => 238, 'price' => 2000000],
            ]);

        app(FillCalculatedPriceCalendar::class)->execute(61);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
