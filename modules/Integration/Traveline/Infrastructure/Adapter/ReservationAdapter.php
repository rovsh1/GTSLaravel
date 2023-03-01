<?php

namespace Module\Integration\Traveline\Infrastructure\Adapter;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Integration\Traveline\Application\Dto\Reservation;
use Module\Integration\Traveline\Application\Dto\ReservationDto;
use Module\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;
use Module\Shared\Infrastructure\Adapter\AbstractPortAdapter;

class ReservationAdapter extends AbstractPortAdapter implements ReservationAdapterInterface
{
    /**
     * Бронирования делаются в вашей системы из свободной квоты, передаются в средство размещения автоматически и не нуждаются в дополнительном подтверждении со стороны отеля. Задача данной функции обеспечить подтверждение факта успешности технической доставки бронирования из вашей системы в менеджер каналов.
     * Если менеджер каналов НЕ подтверждает получение брони ответом, содержащим «success»: true, то каналу необходимо хранить и отдавать в последующих запросах данное бронирование на своей стороне до момента подтверждения менеджером каналов его получения.
     * Менеджер каналов подтверждает факт успешного приема бронирований функцией GetBookingsActionRS.
     * @param int $id
     * @return void
     */
    public function confirmReservation(int $id): void
    {
        // TODO: Implement confirmReservation() method.
        //@todo скорее всего придется делать таблицу traveline_reservations, чтобы хранить статус брони + флаг (принят/не принят тревелайном) + тогда делать и это modules/Integration/Traveline/Application/Service/ReservationFinder.php:44 в ORM

        //@todo логика: помечаем флагом последнюю бронь по номеру и статусу
    }

    public function getActiveReservations(): array
    {
        //@todo В примере запрашиваются все бронирования, которые были сделаны/ модифицированы/ аннулированы в канале и их статус не был подтвержден менеджером каналов методом ConfirmBookingsActionRQ.

        /**
         * 1. Бронь создана (id 123)
         * -- создал запись в ОРМ
         * 2. Бронь изменена (id 123)
         * -- изменил запись в ОРМ
         * -- Тревелайн запросил брони
         * 3. Бронь изменена (id 123)
         * 4. Бронь изменена (id 123)
         */
        /**
         * reservation_id
         * status
         * data (json DTO со всеми полями)
         * created_at
         * updated_at
         * accepted_at
         */
        $reservationsDto = $this->request('hotelReservation/searchActiveReservations');
        return $this->buildReservationDtos($reservationsDto);
    }

    public function getActiveReservationsByHotelId(int $hotelId): array
    {
        $reservationsDto = $this->request('hotelReservation/searchActiveReservations', ['hotel_id' => $hotelId]);
        return $this->buildReservationDtos($reservationsDto);
    }

    public function getActiveReservationById(int $id): ?ReservationDto
    {
        $reservationDto = $this->request('hotelReservation/findById', ['id' => $id]);
        $reservations = $this->buildReservationDtos([$reservationDto]);
        return \Arr::first($reservations);
    }

    public function getUpdatedReservations(CarbonInterface $startDate, ?int $hotelId = null): array
    {
        //@todo ВАЖНО: Если параметр startTime опущен, то должны возвращаться только не подтвержденные (без флага в моей ОРМ), если параметр передан - игнорируем флаг
        $reservationsDto = $this->request('hotelReservation/searchUpdatedReservations', [
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
            $customerDto = Reservation\CustomerDto::from($reservation->reservation->client);
            $rooms = array_map(function ($room) use ($customerDto, $reservation) {
                //@todo перенести в резервейшн модуль калькуляцию цен по дням
                $bookingPerDayPrices = $this->buildRoomPerDayPrices($reservation->reservation->reservationPeriod, $room->priceNetto);

                return Reservation\RoomDto::from($room)->additional([
                    'customer' => $customerDto,
                    'bookingPerDayPrices' => $bookingPerDayPrices
                ]);
            }, $reservation->rooms);

            return ReservationDto::from($reservation->reservation)->additional([
                'roomStays' => $rooms,
                'currencyCode' => env('DEFAULT_CURRENCY_CODE')
            ]);
        }, $reservations);
    }

    /**
     * @param mixed $reservation
     * @return Reservation\Room\DayPriceDto[]
     */
    private function buildRoomPerDayPrices(CarbonPeriod $period, float $allDaysPrice): array
    {
        //@todo вынести в бронирование цену за один день (средняя стоимость дня) в домене
        $countDays = $period->count();
        $dailyPrice = $allDaysPrice / $countDays;
        $prices = [];
        foreach ($period as $date) {
            $prices[] = new Reservation\Room\DayPriceDto($date->format('Y-m-d'), $dailyPrice);
        }
        return $prices;
    }
}
