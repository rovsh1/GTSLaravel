<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid;

use Module\Booking\Moderation\Application\Service\CarBidUpdateHelper;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Remove implements UseCaseInterface
{
    public function __construct(
        private readonly CarBidUpdateHelper $carBidUpdateHelper,
    ) {}

    public function execute(int $bookingId, string $carBidId): void
    {
        $this->carBidUpdateHelper->boot($bookingId);
        $details = $this->carBidUpdateHelper->details();
        $details->removeCarBid($carBidId);
        $this->carBidUpdateHelper->commit();
    }
}
