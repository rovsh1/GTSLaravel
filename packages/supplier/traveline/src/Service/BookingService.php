<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Service;


use Carbon\CarbonInterface;
use Pkg\Supplier\Traveline\Adapters\BookingAdapter;
use Pkg\Supplier\Traveline\Dto\Request\Reservation;
use Pkg\Supplier\Traveline\Dto\ReservationDto;
use Pkg\Supplier\Traveline\Exception\HotelNotConnectedException;
use Pkg\Supplier\Traveline\Http\Response\Error\TravelineResponseErrorInterface;
use Pkg\Supplier\Traveline\Repository\HotelRepository;

class BookingService
{
    /**
     * @var TravelineResponseErrorInterface[] $errors
     */
    private array $errors = [];

    public function __construct(
        private readonly HotelRepository $hotelRepository,
        private readonly BookingAdapter $adapter
    ) {}

    /**
     * @param int|null $reservationId
     * @param int|null $hotelId
     * @param CarbonInterface|null $dateUpdate
     * @return ReservationDto[]
     */
    public function getReservations(
        ?int $reservationId = null,
        ?int $hotelId = null,
        ?CarbonInterface $dateUpdate = null
    ): array {
        if ($hotelId !== null && !$this->hotelRepository->isHotelIntegrationEnabled($hotelId)) {
            throw new HotelNotConnectedException();
        }

        $reservations = [];
        if ($reservationId === null && $hotelId === null && $dateUpdate === null) {
            $reservations = $this->adapter->getActiveReservations();
        } elseif ($dateUpdate !== null) {
            $reservations = $this->adapter->getUpdatedReservations($dateUpdate, $hotelId);
        } elseif ($dateUpdate === null && $hotelId !== null) {
            $reservations = $this->adapter->getActiveReservationsByHotelId($hotelId);
        } elseif ($reservationId !== null) {
            $reservation = $this->adapter->getReservationById($reservationId);
            if ($reservation !== null) {
                $reservations[] = $reservation;
            }
        }

        return $reservations;
    }

    /**
     * @param Reservation[] $reservations
     * @return TravelineResponseErrorInterface[]
     */
    public function confirmReservations(array $reservations): array
    {
        foreach ($reservations as $reservationRequest) {
            try {
                $this->adapter->confirmReservation($reservationRequest->number, $reservationRequest->status);
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
