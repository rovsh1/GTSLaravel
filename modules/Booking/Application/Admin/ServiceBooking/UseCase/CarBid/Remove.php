<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid;

use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Remove extends AbstractCarBidUseCase implements UseCaseInterface
{
    public function execute(int $bookingId, string $carBidId): void
    {
        $details = $this->getBookingDetails(new BookingId($bookingId));
        $details->removeCarBid($carBidId);
        $this->storeDetails($details);
    }
}
