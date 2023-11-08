<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Application\UseCase;

use Module\Booking\Pricing\Domain\Booking\Service\PriceCalculator;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class RecalculatePrices implements UseCaseInterface
{
    public function __construct(
        private readonly PriceCalculator $priceCalculator
    ) {}

    public function execute(int $bookingId): void
    {
        $this->priceCalculator->calculate(new BookingId($bookingId));
    }
}
