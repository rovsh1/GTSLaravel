<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Application\UseCase;

use Module\Booking\Pricing\Domain\Booking\Service\RecalculatePriceService;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class RecalculatePrices implements UseCaseInterface
{
    public function __construct(
        private readonly RecalculatePriceService $recalculatePriceService,
    ) {
    }

    public function execute(int $bookingId): void
    {
        $this->recalculatePriceService->recalculate(new BookingId($bookingId));
    }
}
