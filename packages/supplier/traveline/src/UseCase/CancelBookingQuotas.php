<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\UseCase;

use Pkg\Supplier\Traveline\Repository\RoomQuotaRepository;

class CancelBookingQuotas
{
    public function __construct(
        private readonly RoomQuotaRepository $quotaRepository
    ) {}

    public function execute(int $bookingId): void
    {
        $this->quotaRepository->cancelQuotasReserve($bookingId);
    }
}
