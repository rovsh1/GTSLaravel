<?php

namespace Module\Hotel\Quotation\Application\UseCase;

use Module\Hotel\Quotation\Domain\Repository\QuotaRepositoryInterface;
use Module\Hotel\Quotation\Domain\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CancelBooking implements UseCaseInterface
{
    public function __construct(
        private readonly QuotaRepositoryInterface $quotaRepository
    ) {
    }

    public function execute(int $bookingId): void
    {
        $this->quotaRepository->cancelBooking(new BookingId($bookingId));
    }
}