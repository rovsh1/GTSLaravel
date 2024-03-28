<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\UseCase;

use Carbon\CarbonInterface;
use Pkg\Supplier\Traveline\Service\BookingService;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetReservations implements UseCaseInterface
{
    public function __construct(
        private readonly BookingService $bookingService,
    ) {}

    public function execute(
        ?int $reservationId = null,
        ?int $hotelId = null,
        ?CarbonInterface $dateUpdate = null
    ): array {
        return $this->bookingService->getReservations($reservationId, $hotelId, $dateUpdate);
    }
}
