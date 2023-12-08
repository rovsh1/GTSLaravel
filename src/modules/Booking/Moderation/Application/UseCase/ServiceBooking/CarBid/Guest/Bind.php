<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid\Guest;

use Module\Booking\Moderation\Application\Exception\TooManyCarBidGuestsException;
use Module\Booking\Moderation\Application\Service\GuestValidator;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\CarBidId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Bind implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly GuestValidator $guestValidator,
        private readonly CarBidDbContextInterface $carBidDbContext,
    ) {}

    public function execute(int $bookingId, int $carBidId, int $guestId): void
    {
        $carBid = $this->carBidDbContext->findOrFail(new CarBidId($carBidId));

        $this->guestValidator->ensureCanBindToBooking($bookingId, $guestId);
        $this->ensureGuestsCountAllowed($carBid);

        $this->bookingUnitOfWork->persist($carBid);
        $carBid->addGuest(new GuestId($guestId));
        $this->bookingUnitOfWork->commit();
    }

    private function ensureGuestsCountAllowed(CarBid $carBid): void
    {
        $expectedGuestCount = $carBid->guestIds()->count() + 1;
        //@todo перенести валидацию в сервис
        $allPassengers = $carBid->details()->babyCount() + $carBid->details()->passengersCount();
        if ($expectedGuestCount > $allPassengers) {
            throw new TooManyCarBidGuestsException();
        }
    }
}
