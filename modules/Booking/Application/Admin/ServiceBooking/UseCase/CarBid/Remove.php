<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid;

use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Remove implements UseCaseInterface
{
    public function __construct(
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
    ) {}

    public function execute(int $bookingId, string $carBidId): void
    {
        $id = new BookingId($bookingId);
        $repository = $this->detailsRepositoryFactory->buildByBookingId($id);
        $details = $repository->find($id);
        $details->removeCarBid($carBidId);
        $repository->store($details);
    }
}
