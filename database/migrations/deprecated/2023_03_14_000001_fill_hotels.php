<?php

use App\Admin\Enums\Hotel\VisibilityEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Domain\ValueObject\MarkupSettings\CancelMarkupOption;
use Module\Hotel\Domain\ValueObject\MarkupSettings\CancelPeriod;
use Module\Hotel\Domain\ValueObject\MarkupSettings\CancelPeriodCollection;
use Module\Hotel\Domain\ValueObject\MarkupSettings\CancelPeriodTypeEnum;
use Module\Hotel\Domain\ValueObject\MarkupSettings\ClientMarkups;
use Module\Hotel\Domain\ValueObject\MarkupSettings\Condition;
use Module\Hotel\Domain\ValueObject\MarkupSettings\DailyMarkupCollection;
use Module\Hotel\Domain\ValueObject\MarkupSettings\DailyMarkupOption;
use Module\Hotel\Domain\ValueObject\MarkupSettings\EarlyCheckInCollection;
use Module\Hotel\Domain\ValueObject\MarkupSettings\LateCheckOutCollection;
use Module\Hotel\Domain\ValueObject\MarkupSettings\MarkupSettings;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\TimePeriod;

return new class extends Migration {

    private const TOUR_FEE = 12;
    private const VAT = 13;

    private const CONDITION_CHECK_IN = 1;
    private const CONDITION_CHECK_OUT = 2;

    private const OTA = 1;
    private const TA = 2;
    private const TO = 3;
    private const INDIVIDUAL = 4;

    public function up()
    {
        $q = DB::connection('mysql_old')->table('hotels');
        foreach ($q->cursor() as $r) {
            $hotelId = $r->id;

            $conditionsIndexedByType = DB::connection('mysql_old')
                ->table('hotel_residence_conditions')
                ->where('hotel_id', $hotelId)
                ->get()
                ->groupBy('type');

            $earlyCheckIn = null;
            $earlyCheckInConditions = $conditionsIndexedByType
                ->get(self::CONDITION_CHECK_IN)
                ?->map(fn(\stdClass $condition) => new Condition(
                    new TimePeriod(
                        $condition->start,
                        $condition->end,
                    ),
                    new Percent($condition->price_markup)
                ));
            $earlyCheckIn = new EarlyCheckInCollection(
                $earlyCheckInConditions !== null ? $earlyCheckInConditions->all() : []
            );

            $lateCheckOut = null;
            $lateCheckOutConditions = $conditionsIndexedByType
                ->get(self::CONDITION_CHECK_OUT)
                ?->map(fn(\stdClass $condition) => new Condition(
                    new TimePeriod(
                        $condition->start,
                        $condition->end,
                    ),
                    new Percent($condition->price_markup)
                ));
            $lateCheckOut = new LateCheckOutCollection(
                $lateCheckOutConditions !== null ? $lateCheckOutConditions->all() : []
            );

            $margins = DB::connection('mysql_old')
                ->table('hotel_margins')
                //@todo room_id = null потому что инфа по комнатам будет содержаться в комнатах
                ->select([
                    \DB::raw(
                        "(SELECT `value` FROM hotel_margins WHERE hotel_id = {$hotelId} AND room_id IS NULL AND client_model = " . self::OTA . ') as `OTA`'
                    ),
                    \DB::raw(
                        "(SELECT `value` FROM hotel_margins WHERE hotel_id = {$hotelId} AND room_id IS NULL AND client_model = " . self::TA . ') as `TA`'
                    ),
                    \DB::raw(
                        "(SELECT `value` FROM hotel_margins WHERE hotel_id = {$hotelId} AND room_id IS NULL AND client_model = " . self::TO . ') as `TO`'
                    ),
                    \DB::raw(
                        "(SELECT `value` FROM hotel_margins WHERE hotel_id = {$hotelId} AND room_id IS NULL AND client_model = " . self::INDIVIDUAL . ') as `individual`'
                    ),
                ])
                ->first();

            $clientMarkups = new ClientMarkups(
                new Percent($margins->individual ?? 0),
                new Percent($margins->TA ?? 0),
                new Percent($margins->OTA ?? 0),
                new Percent($margins->TO ?? 0),
            );

            $options = DB::connection('mysql_old')
                ->table('hotel_options')
                ->select([
                    \DB::raw(
                        "(SELECT `value` FROM `hotel_options` WHERE `hotel_id` = {$hotelId} AND `option` = " . self::VAT . ') as vat'
                    ),
                    \DB::raw(
                        "(SELECT `value` FROM `hotel_options` WHERE `hotel_id` = {$hotelId} AND `option` = " . self::TOUR_FEE . ') as touristTax'
                    ),
                ])
                ->first();

            $vatPercent = new Percent(
                is_numeric($options->vat) ? $options->vat : 0
            );
            $touristTaxPercent = new Percent(
                is_numeric($options->touristTax) ? $options->touristTax : 0
            );

            $cancelPeriods = DB::connection('mysql_old')
                ->table('hotel_cancel_period')
                ->where('hotel_id', $hotelId)
                ->get();

            $cancelPeriods = $cancelPeriods->map(function (\stdClass $cancelPeriod) {
                $cancelConditions = DB::connection('mysql_old')
                    ->table('hotel_cancel_conditions')
                    ->where('period_id', $cancelPeriod->id)
                    ->get();

                $dailyMarkups = $cancelConditions->map(
                    fn(\stdClass $dailyMarkup) => new DailyMarkupOption(
                        new Percent($dailyMarkup->price_markup),
                        CancelPeriodTypeEnum::from($dailyMarkup->period_type),
                        $dailyMarkup->days
                    )
                );
                $dailyMarkupCollection = new DailyMarkupCollection(
                    $dailyMarkups->all()
                );

                return new CancelPeriod(
                    new \Carbon\CarbonPeriod($cancelPeriod->date_from, $cancelPeriod->date_to),
                    new CancelMarkupOption(
                        new Percent($cancelPeriod->price_markup),
                        CancelPeriodTypeEnum::from($cancelPeriod->period_type)
                    ),
                    $dailyMarkupCollection
                );
            });
            $cancelPeriodCollection = new CancelPeriodCollection(
                $cancelPeriods->all()
            );

            $markupSettings = new MarkupSettings(
                $vatPercent,
                $touristTaxPercent,
                $clientMarkups,
                $earlyCheckIn,
                $lateCheckOut,
                $cancelPeriodCollection
            );

            DB::table('hotels')
                ->insert([
                    'id' => $hotelId,
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
                    'markup_settings' => json_encode($markupSettings->toData()),
                    'created_at' => $r->created,
                    'updated_at' => $r->updated,
                ]);
        }
    }

    private function getVisibilityValue(?int $visibleFor): VisibilityEnum
    {
        $visibility = VisibilityEnum::PUBLIC;
        if ($visibleFor === 3) {
            $visibility = VisibilityEnum::B2B;
        }
        return $visibility;
    }

    public function down()
    {
        DB::table('hotels')->truncate();
    }
};
