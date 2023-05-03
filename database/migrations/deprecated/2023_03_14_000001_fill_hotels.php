<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    private const TOUR_FEE = 12;
    private const VAT = 13;

    private const CONDITION_CHECK_IN = 1;
    private const CONDITION_CHECK_OUT = 2;

    public function up()
    {
        $q = DB::connection('mysql_old')->table('hotels');
        foreach ($q->cursor() as $r) {
//            $conditionsIndexedByType = DB::connection('mysql_old')
//                ->table('hotel_residence_conditions')
//                ->where('hotel_id', $r->id)
//                ->groupBy('type')
//                ->get();
//
//            //@todo что делать с room_id?
//            $margins = DB::connection('mysql_old')
//                ->table('hotel_margins')
//                ->where('hotel_id', $r->id)
//                ->get();
//
//            $options = DB::connection('mysql_old')
//                ->table('hotel_options')
//                ->where('hotel_id', $r->id)
//                ->whereIn('option', [self::TOUR_FEE, self::VAT])
//                ->get();
//
//            $cancelPeriods = DB::connection('mysql_old')
//                ->table('hotel_cancel_period')
//                ->where('hotel_id', $r->id)
//                ->get();
//
//            $cancelConditions = collect();
//            foreach ($cancelPeriods as $cancelPeriod) {
//                $cancelConditions = DB::connection('mysql_old')
//                    ->table('hotel_cancel_conditions')
//                    ->where('period_id', $cancelPeriod->id)
//                    ->get();
//            }
//
//
//            $markupSettingsJson = null;
//            if ($conditionsIndexedByType->count() > 0) {
//
//            }

            DB::table('hotels')
                ->insert([
                    'id' => $r->id,
                    'city_id' => $r->city_id,
                    'type_id' => $r->type_id,
                    'rating' => $r->rating,
                    'name' => $r->name,
                    'address' => $r->address,
                    'address_lat' => $r->latitude,
                    'address_lon' => $r->longitude,
                    'city_distance' => $r->citycenter_distance,
                    'zipcode' => $r->zipcode,
                    'status' => $r->status,
                    'visibility' => $this->getVisibilityValue($r->visible_for),
                    'markup_settings' => null,
                    'created_at' => $r->created,
                    'updated_at' => $r->updated,
                ]);
        }
    }

    private function getVisibilityValue(?int $visibleFor): \App\Admin\Enums\Hotel\VisibilityEnum
    {
        $visibility = \App\Admin\Enums\Hotel\VisibilityEnum::PUBLIC;
        if ($visibleFor === 3) {
            $visibility = \App\Admin\Enums\Hotel\VisibilityEnum::B2B;
        }
        return $visibility;
    }

    public function down()
    {
        DB::table('hotels')->truncate();
    }
};
