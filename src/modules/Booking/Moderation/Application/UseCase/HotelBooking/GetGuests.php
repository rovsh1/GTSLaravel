<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking;

use Module\Booking\Moderation\Application\Dto\GuestDto;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetGuests implements UseCaseInterface
{
    public function __construct(
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly GuestRepositoryInterface $guestRepository,
    ) {}

    public function execute(int $bookingId): array
    {
        $accommodations = $this->accommodationRepository->getByBookingId(new BookingId($bookingId));
        $guestIds = $accommodations->map(fn(HotelAccommodation $accommodation) => $accommodation->guestIds()->all());
        $guestIds = new GuestIdCollection(array_merge(...$guestIds));

        $guests = $this->guestRepository->get($guestIds);

        return GuestDto::collectionFromDomain($guests);
    }
}
