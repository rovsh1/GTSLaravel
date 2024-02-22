<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation;

use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UnbindGuest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
    ) {}

    public function execute(int $bookingId, int $accommodationId, int $guestId): void
    {
        $accommodation = $this->accommodationRepository->findOrFail(new AccommodationId($accommodationId));
        if (!$accommodation->bookingId()->isEqual($bookingId)) {
            throw new \Exception('');
        }

        $this->bookingUnitOfWork->persist($accommodation);
        $accommodation->removeGuest(new GuestId($guestId));
        $this->bookingUnitOfWork->commit();
    }
}
