<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\UseCase;

use Pkg\Supplier\Traveline\Dto\Request\Reservation;
use Pkg\Supplier\Traveline\Dto\Response\Error\TravelineResponseErrorInterface;
use Pkg\Supplier\Traveline\Service\BookingService;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class ConfirmReservations implements UseCaseInterface
{
    public function __construct(
        private readonly BookingService $bookingService,
    ) {}

    /**
     * @param Reservation[] $reservations
     * @return TravelineResponseErrorInterface[]
     */
    public function execute(array $reservations): array
    {
        return $this->bookingService->confirmReservations($reservations);
    }
}
