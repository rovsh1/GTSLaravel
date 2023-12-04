<?php

namespace Supplier\Traveline\Application\Service;

use Supplier\Traveline\Domain\Adapter\ReservationAdapterInterface;
use Supplier\Traveline\Domain\Api\Request\Reservation;
use Supplier\Traveline\Domain\Api\Response\Error\TravelineResponseErrorInterface;

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
//                if (!$e->getPrevious() instanceof DomainEntityExceptionInterface) {
//                    throw $e;
//                }
//                if ($e->getPrevious()->domainCode() === ErrorCodeEnum::RESERVATION_NOT_FOUND) {
//                    $this->errors[] = new ReservationNotFound($reservationRequest->number);
//                }
                //@todo отлов других ошибок (пока непонятно какие могут быть ошибки)
            }
        }
        return $this->errors;
    }
}
