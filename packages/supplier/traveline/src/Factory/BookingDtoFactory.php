<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Factory;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Booking\Moderation\Application\Dto\Details\Accommodation\RoomDayPriceDto;
use Module\Booking\Moderation\Application\Dto\Details\AccommodationDto;
use Module\Booking\Moderation\Application\Dto\Details\ConditionDto;
use Module\Booking\Moderation\Application\Dto\Details\HotelInfoDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\BookingDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\HotelBookingDto;
use Pkg\Supplier\Traveline\Dto\Reservation\CustomerDto;
use Pkg\Supplier\Traveline\Dto\Reservation\Room\DayPriceDto;
use Pkg\Supplier\Traveline\Dto\Reservation\Room\GuestDto;
use Pkg\Supplier\Traveline\Dto\Reservation\Room\TotalDto;
use Pkg\Supplier\Traveline\Dto\Reservation\RoomStayDto;
use Pkg\Supplier\Traveline\Dto\ReservationDto;
use Pkg\Supplier\Traveline\Models\TravelineReservation;
use Pkg\Supplier\Traveline\Models\TravelineReservationStatusEnum;
use Sdk\Booking\Enum\StatusEnum;

class BookingDtoFactory
{
    private const DAY_PRICE_DATE_FORMAT = 'Y-m-d';

    public function __construct(
        private readonly string $defaultTimezone,
    ) {}

    public function build(BookingDto $booking): ReservationDto
    {
        /** @var HotelBookingDto $details */
        $details = $booking->details;

        $bookingPeriod = new CarbonPeriod($details->period->dateFrom, $details->period->dateTo);
        $bookingPeriod->setTimezone($this->defaultTimezone);

        return new ReservationDto(
            number: $booking->id,
            hotelId: $details->hotelInfo->id,
            created: new CarbonImmutable($booking->createdAt),
            arrivalTime: $details->hotelInfo->checkInTime,
            departureTime: $details->hotelInfo->checkOutTime,
            currencyCode: $booking->prices->supplierPrice->currency->value,
            roomStays: array_map(
                fn(AccommodationDto $accommodation) => $this->convertAccommodationToRoomStay(
                    $bookingPeriod,
                    $details->hotelInfo,
                    $accommodation
                ),
                $details->roomBookings
            ),
            additionalInfo: $this->buildAdditionalInfo($bookingPeriod, $details->roomBookings),
            customer: $this->getDefaultCustomer(null),//@todo получение емейла менеджера
            status: $this->getTravelineStatus($booking),
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

    public function convertAccommodationToRoomStay(
        CarbonPeriod $period,
        HotelInfoDto $hotelInfo,
        AccommodationDto $accommodation
    ): RoomStayDto {
        return new RoomStayDto(
            $accommodation->roomInfo->id,
            $accommodation->details->rateId,
            GuestDto::collection($accommodation->guests),
            count($accommodation->guests),
            $this->buildRoomPerDayPrices($period, $hotelInfo, $accommodation),
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
    private function buildRoomPerDayPrices(
        CarbonPeriod $period,
        HotelInfoDto $hotelInfo,
        AccommodationDto $accommodation
    ): array {
        $startDate = $this->getPeriodStartDateByCheckInCondition($period, $hotelInfo->checkInTime, $accommodation->details->earlyCheckIn);
        $endDate = $this->getPeriodEndDateByCheckOutCondition($period, $hotelInfo->checkOutTime, $accommodation->details->lateCheckOut);
        $preparedPeriod = new CarbonPeriod($startDate, $endDate, 'P1D');

        $countDays = $preparedPeriod->count();
        $dailyPrice = $accommodation->price->netValue / $countDays;
        $prices = [];
        foreach ($preparedPeriod as $date) {
            $prices[] = new DayPriceDto($date->format(self::DAY_PRICE_DATE_FORMAT), $dailyPrice);
        }

        return $prices;
    }

    private function getPeriodStartDateByCheckInCondition(
        CarbonPeriod $period,
        ?string $hotelDefaultCheckInStart,
        ?ConditionDto $roomCheckInCondition
    ): CarbonInterface {
        if ($hotelDefaultCheckInStart === null) {
            \Log::warning('У отеля отсутствует дефолтное время заезда');

            return $period->getStartDate();
        }
        if ($roomCheckInCondition === null) {
            return $period->getStartDate();
        }
        $startDate = $period->getStartDate()->clone();
        if ($roomCheckInCondition->from < $hotelDefaultCheckInStart) {
            $startDate->subDay();
        }

        return $startDate;
    }

    private function getPeriodEndDateByCheckOutCondition(
        CarbonPeriod $period,
        string $hotelDefaultCheckOutEnd,
        ?ConditionDto $roomCheckOutCondition
    ): CarbonInterface {
        //кол-во ночей считается на 1 день меньше периода
        $endDate = $period->getEndDate()->clone()->subDay();
        if ($hotelDefaultCheckOutEnd === null) {
            \Log::warning('У отеля отсутствует дефолтное время выезда');

            return $endDate;
        }
        if ($roomCheckOutCondition === null) {
            return $endDate;
        }
        if ($roomCheckOutCondition->to > $hotelDefaultCheckOutEnd) {
            $endDate->addDay();
        }

        return $endDate;
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

    private function getTravelineStatus(BookingDto $booking): TravelineReservationStatusEnum
    {
        $statusEnum = StatusEnum::from($booking->status->id);
        if ($this->isCancelledStatus($statusEnum)) {
            return TravelineReservationStatusEnum::CANCELLED;
        }

        $travelineStatus = TravelineReservationStatusEnum::NEW;

        $travelineReservation = TravelineReservation::whereReservationId($booking->id)->first();
        if ($travelineReservation !== null && $travelineReservation->created_at->notEqualTo($travelineReservation->updated_at)) {
            $travelineStatus = TravelineReservationStatusEnum::MODIFIED;
        }

        return $travelineStatus;
    }

    private function isCancelledStatus(StatusEnum $status): bool
    {
        return in_array($status, [
            StatusEnum::CANCELLED,
            StatusEnum::CANCELLED_FEE,
            StatusEnum::CANCELLED_NO_FEE,
            StatusEnum::DELETED,
            StatusEnum::WAITING_CANCELLATION,
        ]);
    }
}
