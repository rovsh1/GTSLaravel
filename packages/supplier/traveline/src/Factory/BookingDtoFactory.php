<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Factory;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Module\Booking\Moderation\Application\Dto\Details\Accommodation\RoomDayPriceDto;
use Module\Booking\Moderation\Application\Dto\Details\AccommodationDto;
use Module\Booking\Moderation\Application\Dto\Details\ConditionDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\BookingDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\HotelBookingDto;
use Pkg\Supplier\Traveline\Dto\Reservation\CustomerDto;
use Pkg\Supplier\Traveline\Dto\Reservation\Room\DayPriceDto;
use Pkg\Supplier\Traveline\Dto\Reservation\Room\GuestDto;
use Pkg\Supplier\Traveline\Dto\Reservation\Room\TotalDto;
use Pkg\Supplier\Traveline\Dto\Reservation\RoomStayDto;
use Pkg\Supplier\Traveline\Dto\ReservationDto;
use Pkg\Supplier\Traveline\Models\TravelineReservationStatusEnum;

class BookingDtoFactory
{
    private const DAY_PRICE_DATE_FORMAT = 'Y-m-d';

    public function build(BookingDto $booking): ReservationDto
    {
        /** @var HotelBookingDto $details */
        $details = $booking->details;

        $bookingPeriod = new CarbonPeriod($details->period->dateFrom, $details->period->dateTo);

        return new ReservationDto(
            number: $booking->id,
            hotelId: $details->hotelInfo->id,
            created: new CarbonImmutable($booking->createdAt),
            arrivalTime: $details->hotelInfo->checkInTime,
            departureTime: $details->hotelInfo->checkOutTime,
            currencyCode: $booking->prices->supplierPrice->currency->value,
            roomStays: array_map(
                fn(AccommodationDto $accommodation) => $this->convertAccommodationToRoomStay($bookingPeriod, $accommodation),
                $details->roomBookings
            ),
            additionalInfo: $this->buildAdditionalInfo($bookingPeriod, $details->roomBookings),
            customer: $this->getDefaultCustomer(null),//@todo получение емейла менеджера
            status: TravelineReservationStatusEnum::New,//@todo конвертация статуса
        );
    }

    /**
     * @param BookingDto[] $bookings
     * @return ReservationDto[]
     */
    public function collection(array $bookings): array
    {
        return array_map(fn(BookingDto $booking) => $this->build($booking), $bookings);
    }

    public function convertAccommodationToRoomStay(CarbonPeriod $period, AccommodationDto $accommodation): RoomStayDto
    {
        return new RoomStayDto(
            $accommodation->roomInfo->id,
            $accommodation->details->rateId,
            GuestDto::collection($accommodation->guestIds),//@todo получить имена гостей и т.п. из заказа
            count($accommodation->guestIds),
            $this->buildRoomPerDayPrices($period, $accommodation),
            new TotalDto($accommodation->price->netValue),
            $accommodation->details->guestNote,
        );
    }

    /**
     * @param HotelBookingDto $details
     * @param RoomDayPriceDto[] $dayPrices
     * @return DayPriceDto[]
     * @todo тут временно будет логика квотирования раннего/позднего заезда/выезда:
     *   - если ранний заезд - включаем день перед началом периода
     *   - если поздний заезд - включаем последний день периода
     */
    private function buildRoomPerDayPrices(CarbonPeriod $period, AccommodationDto $accommodation): array
    {
        $dayPrices = [];

        $countDays = $period->count();
        $dailyPrice = $accommodation->price->netValue / $countDays;
        foreach ($period as $date) {
            $dayPrices[] = new DayPriceDto($date->format('Y-m-d'), $dailyPrice);
        }
dd($accommodation);
//        /** @var DayPriceDto[] $preparedPrices */
//        $preparedPrices = array_map(
//            fn(RoomDayPriceDto $dayPrice) => new DayPriceDto(
//                date(self::DAY_PRICE_DATE_FORMAT, strtotime($dayPrice->date)),
//                $dayPrice->netValue
//            ),
//            $dayPrices
//        );

        $earlyCheckIn = $accommodation->details->earlyCheckIn;
        if ($earlyCheckIn !== null) {
            $firstDayPrice = array_shift($dayPrices);
            $firstDayPriceWithoutMarkupValue = $firstDayPrice->price * 100 / ($earlyCheckIn->percent + 100);
            $firstDayPriceWithoutMarkup = new DayPriceDto($firstDayPrice->dateYmd, $firstDayPriceWithoutMarkupValue);
            $beforeFirstDayPrice = new DayPriceDto(
                (new Carbon($firstDayPrice->dateYmd))->subDay()->format(self::DAY_PRICE_DATE_FORMAT),
                $firstDayPrice->price - $firstDayPriceWithoutMarkupValue
            );
            array_unshift($dayPrices, $beforeFirstDayPrice, $firstDayPriceWithoutMarkup);
        }

        $lateCheckOut = $accommodation->details->lateCheckOut;
        if ($lateCheckOut !== null) {
            $lastDayPrice = array_pop($dayPrices);
            $lastDayPriceWithoutMarkupValue = $lastDayPrice->price * 100 / ($lateCheckOut->percent + 100);
            $lastDayPriceWithoutMarkup = new DayPriceDto($lastDayPrice->dateYmd, $lastDayPriceWithoutMarkupValue);
            $afterLastDayPrice = new DayPriceDto(
                (new Carbon($lastDayPrice->dateYmd))->addDay()->format(self::DAY_PRICE_DATE_FORMAT),
                $lastDayPrice->price - $lastDayPriceWithoutMarkupValue
            );
            $dayPrices[] = $lastDayPriceWithoutMarkup;
            $dayPrices[] = $afterLastDayPrice;
        }


        return $dayPrices;
    }

    private function getDefaultCustomer(?string $managerEmail): CustomerDto
    {
        return new CustomerDto(
            'GoToStans',
            null,
            null,
            $managerEmail ?? 'info@gotostans.com',
            '+998 78 120-90-12',
        );
    }

    /**
     * @param CarbonPeriod $period
     * @param AccommodationDto[] $accommodations
     * @return string|null
     */
    private function buildAdditionalInfo(CarbonPeriod $period, array $accommodations): ?string
    {
        $roomsAdditionalInfo = collect($accommodations)->map(
            fn(AccommodationDto $accommodation) => $this->buildRoomAdditionalInfo(
                $accommodation->roomInfo->name,
                $period,
                $accommodation->details->earlyCheckIn,
                $accommodation->details->lateCheckOut
            )
        )->filter();

        return $roomsAdditionalInfo->isNotEmpty() ? $roomsAdditionalInfo->implode("\n") : null;
    }

    private function buildRoomAdditionalInfo(
        string $roomName,
        CarbonPeriod $period,
        ?ConditionDto $roomCheckInCondition,
        ?ConditionDto $roomCheckOutCondition
    ): ?string {
        $comment = '';
        if ($roomCheckInCondition !== null) {
            $checkInDate = $period->getStartDate()->format('d.m.Y');
            $comment .= "Фактическое время заезда (ранний заезд) с {$roomCheckInCondition->from} {$checkInDate}. ";
        }
        if ($roomCheckOutCondition !== null) {
            $checkOutDate = $period->getEndDate()->format('d.m.Y');
            $comment .= "Фактическое время выезда (поздний выезд) до {$roomCheckOutCondition->to} {$checkOutDate}";
        }

        return empty($comment) ? null : $roomName . ': ' . $comment;
    }
}
