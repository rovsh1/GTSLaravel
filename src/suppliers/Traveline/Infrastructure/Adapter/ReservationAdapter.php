<?php

namespace Supplier\Traveline\Infrastructure\Adapter;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Integration\Traveline\Application\Dto\Reservation;
use Module\Shared\Infrastructure\Adapter\AbstractModuleAdapter;
use Supplier\Traveline\Application\Dto\ReservationDto;
use Supplier\Traveline\Domain\Adapter\ReservationAdapterInterface;

class ReservationAdapter extends AbstractModuleAdapter implements ReservationAdapterInterface
{
    //public function __construct(private readonly ConfigInterface $config) {}

    /**
     * Бронирования делаются в вашей системы из свободной квоты, передаются в средство размещения автоматически и не нуждаются в дополнительном подтверждении со стороны отеля. Задача данной функции обеспечить подтверждение факта успешности технической доставки бронирования из вашей системы в менеджер каналов.
     * Если менеджер каналов НЕ подтверждает получение брони ответом, содержащим «success»: true, то каналу необходимо хранить и отдавать в последующих запросах данное бронирование на своей стороне до момента подтверждения менеджером каналов его получения.
     * Менеджер каналов подтверждает факт успешного приема бронирований функцией GetBookingsActionRS.
     * @param int $id
     * @return void
     */
    public function confirmReservation(int $id, string $status): void
    {
        // TODO: Implement confirmReservation() method.
        //todo логика: помечаем флагом последнюю бронь по номеру и статусу
        //todo логика проверки на наличие брони с таким кодом
        //todo перевести бронь в статус "Подтверждена"
    }

    public function getActiveReservations(): array
    {
        $reservationsDto = $this->request('searchActiveReservations');
        return $this->buildReservationDtos($reservationsDto);
    }

    public function getActiveReservationsByHotelId(int $hotelId): array
    {
        $reservationsDto = $this->request('searchActiveReservations', ['hotel_id' => $hotelId]);
        return $this->buildReservationDtos($reservationsDto);
    }

    public function getActiveReservationById(int $id): ?ReservationDto
    {
        $reservationDto = $this->request('findById', ['id' => $id]);
        $reservations = $this->buildReservationDtos([$reservationDto]);
        return $reservations[0] ?? null;
    }

    public function getUpdatedReservations(CarbonInterface $startDate, ?int $hotelId = null): array
    {
        $reservationsDto = $this->request('searchUpdatedReservations', [
            'date_update' => $startDate,
            'hotel_id' => $hotelId
        ]);
        return $this->buildReservationDtos($reservationsDto);
    }

    /**
     * @param array $reservations
     * @return ReservationDto[]
     */
    private function buildReservationDtos(array $reservations): array
    {
        return array_map(function ($reservation) {
            $customerDto = \Supplier\Traveline\Application\Dto\Reservation\CustomerDto::from($reservation->reservation->client);
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
            $prices[] = new \Supplier\Traveline\Application\Dto\Reservation\Room\DayPriceDto($date->format('Y-m-d'), $dailyPrice);
        }
        return $prices;
    }

    protected function getModuleKey(): string
    {
        return 'hotelReservation';
    }
}