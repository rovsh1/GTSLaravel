<?php

namespace GTS\Hotel\Infrastructure\Facade;

use GTS\Hotel\Domain\Adapter\ReservationAdapterInterface;

class ReservationFacade implements ReservationFacadeInterface
{
    public function __construct(
        private ReservationAdapterInterface $adapter,
    ) {}

    public function getActiveReservations(int $hotelId): array
    {
        return $this->adapter->getActiveReservationsByHotelId($hotelId);
    }
}
