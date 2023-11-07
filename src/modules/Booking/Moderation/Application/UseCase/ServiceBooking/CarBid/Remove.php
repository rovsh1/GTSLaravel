<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid;

use Module\Booking\Domain\Booking\Service\TransferBooking\CarBidUpdater;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Remove implements UseCaseInterface
{
    public function __construct(
        private readonly CarBidUpdater $carBidUpdater,
    ) {}

    public function execute(int $bookingId, string $carBidId): void
    {
        $this->carBidUpdater->remove(new BookingId($bookingId), $carBidId);
    }
}
