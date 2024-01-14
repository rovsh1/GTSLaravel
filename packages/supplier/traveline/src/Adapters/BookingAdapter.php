<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Adapters;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Booking\Moderation\Application\UseCase\GetBooking;
use Pkg\Supplier\Traveline\Dto\ReservationDto;
use Pkg\Supplier\Traveline\Models\TravelineReservation;

class BookingAdapter
{

    /**
     * Бронирования делаются в вашей системы из свободной квоты, передаются в средство размещения автоматически и не нуждаются в дополнительном подтверждении со стороны отеля. Задача данной функции обеспечить подтверждение факта успешности технической доставки бронирования из вашей системы в менеджер каналов.
     * Если менеджер каналов НЕ подтверждает получение брони ответом, содержащим «success»: true, то каналу необходимо хранить и отдавать в последующих запросах данное бронирование на своей стороне до момента подтверждения менеджером каналов его получения.
     * Менеджер каналов подтверждает факт успешного приема бронирований функцией GetBookingsActionRS.
     * @param int $id
     * @return void
     */
    public function confirmReservation(int $id, string $status): void
    {
        $existReservation = TravelineReservation::whereReservationId($id)->exists();
        if (!$existReservation) {
            throw new \RuntimeException('Traveline reservation not found', 0);
        }
        TravelineReservation::whereReservationId($id)
            ->whereStatus($status)
            ->update(['accepted_at' => now()]);

        /** @var Reservation $reservation */
        $reservation = Reservation::find($id);
        if ($status === TravelineReservationStatusEnum::New) {
            $reservation->changeStatus(ReservationStatusEnum::Confirmed);

            return;
        }
        if ($reservation->status === ReservationStatusEnum::WaitingProcessing) {
            $reservation->changeStatus(ReservationStatusEnum::Confirmed);
        }
    }

    public function getActiveReservations(): array
    {
        $reservations = TravelineReservation::whereNull('accepted_at')->get();

        $preparedReservations = $this->prepareReservationCollection($reservations);

        return ReservationDto::collection($preparedReservations)->all();
    }

    public function getActiveReservationsByHotelId(int $hotelId): array
    {
        $reservations = TravelineReservation::whereNull('accepted_at')
            ->whereHotelId($hotelId)
            ->get();

        $preparedReservations = $this->prepareReservationCollection($reservations);

        return ReservationDto::collection($preparedReservations)->all();
    }

    public function getActiveReservationById(int $id): ?ReservationDto
    {
        $reservation = app(GetBooking::class)->execute($id);
        if ($reservation === null) {
            return null;
        }

        return ReservationDto::from($reservation->data);
    }

    public function getUpdatedReservations(CarbonInterface $startDate, ?int $hotelId = null): array
    {
        $reservations = TravelineReservation::query()
            ->where(function (Builder $builder) use ($hotelId) {
                if ($hotelId !== null) {
                    $builder->whereHotelId($hotelId);
                }
            })
            ->where('updated_at', '>=', $startDate)
            ->get();

        $preparedReservations = $this->prepareReservationCollection($reservations);

        return ReservationDto::collection($preparedReservations)->all();
    }

    /**
     * @param array $reservations
     * @return ReservationDto[]
     */
    private function buildReservationDtos(array $reservations): array
    {
        return array_map(function ($reservation) {
            $customerDto = \Supplier\Traveline\Application\Dto\Reservation\CustomerDto::from(
                $reservation->reservation->client
            );
            $rooms = array_map(function ($room) use ($customerDto, $reservation) {
                $bookingPerDayPrices = $this->buildRoomPerDayPrices(
                    $reservation->reservation->reservationPeriod,
                    $room->priceNetto
                );

                return \Supplier\Traveline\Application\Dto\Reservation\RoomDto::from($room)->additional([
                    'customer' => $customerDto,
                    'bookingPerDayPrices' => $bookingPerDayPrices
                ]);
            }, $reservation->rooms);

            return ReservationDto::from($reservation->reservation)->additional([
                'roomStays' => $rooms,
                //@hack @todo заменить на получение валюты брони
                'currencyCode' => 'UZS'
            ]);
        }, $reservations);
    }

    /**
     * @param CarbonPeriod $period
     * @return \Supplier\Traveline\Application\Dto\Reservation\Room\DayPriceDto[]
     */
    private function buildRoomPerDayPrices(CarbonPeriod $period, float $allDaysPrice): array
    {
        //todo вынести в бронирование цену за один день (средняя стоимость дня) в домене
        $countDays = $period->count();
        $dailyPrice = $allDaysPrice / $countDays;
        $prices = [];
        foreach ($period as $date) {
            $prices[] = new \Supplier\Traveline\Application\Dto\Reservation\Room\DayPriceDto(
                $date->format('Y-m-d'),
                $dailyPrice
            );
        }

        return $prices;
    }
}
