<?php

declare(strict_types=1);

namespace App\Admin\Services\ReportCompiler\Factory;

use App\Admin\Models\Booking\Booking;
use App\Admin\Models\Hotel\Hotel;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Sdk\Booking\Enum\StatusEnum;

class HotelCostsDataFactory
{
    public function build(CarbonPeriod $endPeriod, ?CarbonPeriod $startPeriod = null): array
    {
        $startPeriodCondition = !empty($startPeriod) ? [$startPeriod->getStartDate(), $startPeriod->getEndDate()] : null;
        $endPeriodCondition = [$endPeriod->getStartDate(), $endPeriod->getEndDate()];

        $hotelBookingsQuery = Booking::query()
            ->addSelect('bookings.*')
            ->addSelect('booking_hotel_details.hotel_id as hotel_id')
            ->selectRaw(
                '(select COALESCE(SUM(sum), 0) from supplier_payment_landings where supplier_payment_landings.booking_id = bookings.id) as payed_amount'
            )
            ->selectRaw(
                "(SELECT name FROM hotels WHERE id = booking_hotel_details.hotel_id) as hotel_name"
            )
            ->addSelect('booking_hotel_details.date_start')
            ->addSelect('booking_hotel_details.date_end')
            ->join('booking_hotel_details', 'booking_hotel_details.booking_id', 'bookings.id')
            ->whereIn('bookings.status', [StatusEnum::CONFIRMED, StatusEnum::CANCELLED_FEE])
            ->where(function (Builder $query) use ($startPeriodCondition, $endPeriodCondition) {
                $query->whereBetween('booking_hotel_details.date_end', $endPeriodCondition);
                if (!empty($startPeriodCondition)) {
                    $query->whereBetween('booking_hotel_details.date_start', $startPeriodCondition);
                }
            });

        $hotelCostsData = DB::query()
            ->fromSub($hotelBookingsQuery, 'b')
            ->addSelect('b.hotel_id as hotel_id')
            ->selectRaw('COUNT(b.id) as bookings_count')
            ->selectRaw('SUM(b.payed_amount) as payed_amount')
            ->selectRaw('SUM(COALESCE(b.supplier_manual_price, b.supplier_price)) as hotel_supplier_price')
            ->selectRaw('MAX(b.hotel_name) as hotel_name')
            ->groupBy('b.hotel_id')
            ->get();

        $hotelIds = $hotelCostsData->pluck('hotel_id')->toArray();
        $hotels = [];
        if (count($hotelIds) > 0) {
            $hotels = Hotel::whereIn('hotels.id', $hotelIds)->get()->keyBy('id');
        }

        $hotelCostsData = $hotelCostsData->map(function (\stdClass $hotelData) use ($hotels) {
            $hotel = $hotels[$hotelData->hotel_id] ?? "Отель №{$hotelData->hotel_id}";
            $hotelData->city_name = $hotel->city_name;

            return $hotelData;
        });
    }
}
