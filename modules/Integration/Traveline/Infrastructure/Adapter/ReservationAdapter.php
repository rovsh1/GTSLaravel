<?php

namespace Module\Integration\Traveline\Infrastructure\Adapter;

use Carbon\CarbonInterface;
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
    }

    public function getActiveReservations(): array
    {
        return $this->request('hotelReservation/searchActiveReservations');
    }

    public function getActiveReservationByHotelId(int $hotelId): mixed
    {
        return $this->request('hotelReservation/searchActiveReservations', ['hotel_id' => $hotelId]);
    }

    public function getActiveReservationById(int $id): mixed
    {
        return $this->request('hotelReservation/findById', ['id' => $id]);
    }

    public function getUpdatedReservations(CarbonInterface $startDate, ?int $hotelId = null): array
    {
        return $this->request('hotelReservation/searchUpdatedReservations', [
            'date_update' => $startDate,
            'hotel_id' => $hotelId
        ]);
    }
}
