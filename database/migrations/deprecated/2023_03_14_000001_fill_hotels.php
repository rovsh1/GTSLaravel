<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        $q = DB::connection('mysql_old')->table('hotels');
        foreach ($q->cursor() as $r) {
            $conditions = DB::connection('mysql_old')
                ->table('hotel_residence_conditions')
                ->where('hotel_id', $r->id)
                ->get();

            $conditionsJson = null;
            if ($conditions->count() > 0) {
                $conditionsJson = $conditions->map(fn(\stdClass $condition) => [
                    'type' => $condition->type,
                    'price_markup' => $condition->price_markup,
                    'start_time' => $condition->start,
                    'end_time' => $condition->end,
                ])->toJson();
            }

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
                    'additional_conditions' => $conditionsJson,
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
