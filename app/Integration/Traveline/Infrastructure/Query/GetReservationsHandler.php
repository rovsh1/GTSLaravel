<?php

namespace GTS\Integration\Traveline\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use GTS\Integration\Traveline\Application\Query\GetReservations;
use GTS\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;
use GTS\Integration\Traveline\Domain\Entity\Reservation;
use GTS\Integration\Traveline\Domain\Factory\ReservationFactory;

class GetReservationsHandler implements QueryHandlerInterface
{
    public function __construct(
        private ReservationAdapterInterface $adapter
    ) {}

    /**
     * @param GetReservations $query
     * @return Reservation[]
     */
    public function handle(QueryInterface $query): array
    {
        $reservations = [];
        if ($query->reservationId === null && $query->hotelId === null && $query->dateUpdate === null) {
            $reservations = $this->adapter->getActiveReservations();
        }
        if ($query->dateUpdate !== null) {
            $reservations = $this->adapter->getUpdatedReservations($query->dateUpdate, $query->hotelId);
        }
        if ($query->dateUpdate === null && $query->hotelId !== null) {
            $reservations = $this->adapter->getReservationsByHotelId($query->hotelId);
        }
        if ($query->reservationId !== null) {
            $reservation = $this->adapter->getReservationById($query->reservationId);
            //@todo логика если что-то не так
            $reservations = [$reservation];
        }

        return ReservationFactory::createCollectionFrom($reservations);
    }
}
