<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid\Guest;

use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Unbind implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly CarBidDbContextInterface $carBidDbContext,
    ) {}

    public function execute(int $bookingId, int $carBidId, int $guestId): void
    {
        $carBid = $this->carBidDbContext->findOrFail(new CarBidId($carBidId));
        if (!$carBid->bookingId()->isEqual(new BookingId($bookingId))) {
            throw new \InvalidArgumentException('Incorrect booking and guest request');
        }

        $this->bookingUnitOfWork->persist($carBid);
        $carBid->removeGuest(new GuestId($guestId));
        $this->bookingUnitOfWork->commit();
    }
}
