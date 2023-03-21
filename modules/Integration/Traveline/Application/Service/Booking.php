<?php

namespace Module\Integration\Traveline\Application\Service;

use Module\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;
use Module\Integration\Traveline\Domain\Api\Request\Reservation;
use Module\Integration\Traveline\Domain\Api\Response\Error\TravelineResponseErrorInterface;

class Booking
{
    /**
     * @var TravelineResponseErrorInterface[] $errors
     */
    private array $errors = [];

    public function __construct(private ReservationAdapterInterface $adapter) {}

    /**
     * @param array $reservations
     * @return TravelineResponseErrorInterface[]
     */
    public function confirmReservations(array $reservations): array
    {
        $reservationRequests = Reservation::collectionFromArray($reservations);
        foreach ($reservationRequests as $reservationRequest) {
            try {
                $this->adapter->confirmReservation($reservationRequest->number, $reservationRequest->status->value);
            } catch (\Throwable $e) {
                //@todo логика заполнения ошибок
            }
        }
        return $this->errors;
    }
}
