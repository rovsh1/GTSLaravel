<?php

namespace Module\Integration\Traveline\Application\Service;

use Module\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;
use Module\Integration\Traveline\Domain\Api\Request\Reservation;
use Module\Integration\Traveline\Domain\Api\Response\Error\ReservationNotFound;
use Module\Integration\Traveline\Domain\Api\Response\Error\TravelineResponseErrorInterface;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\TravelineReservationStatusEnum;
use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
use Module\Shared\Domain\Exception\ErrorCodeEnum;

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
                $this->adapter->confirmReservation(
                    $reservationRequest->number,
                    TravelineReservationStatusEnum::from($reservationRequest->status->value)
                );
            } catch (\Throwable $e) {
                if (!$e->getPrevious() instanceof DomainEntityExceptionInterface) {
                    throw $e;
                }
                if ($e->getPrevious()->domainCode() === ErrorCodeEnum::ReservationNotFound) {
                    $this->errors[] = new ReservationNotFound($reservationRequest->number);
                }
                //@todo отлов других ошибок (пока непонятно какие могут быть ошибки)
            }
        }
        return $this->errors;
    }
}
