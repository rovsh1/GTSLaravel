<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository;

use Module\Booking\Shared\Domain\Booking\Entity\HotelAccommodation;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationDetails;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationIdCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPrices;

interface AccommodationRepositoryInterface
{
    public function find(AccommodationId $id): ?HotelAccommodation;

    public function findOrFail(AccommodationId $id): HotelAccommodation;

    public function get(AccommodationIdCollection $accommodationIds): AccommodationCollection;

    public function store(HotelAccommodation $booking): bool;

    public function delete(AccommodationId $id): bool;

    public function create(
        BookingId $bookingId,
        RoomInfo $roomInfo,
        AccommodationDetails $details,
        RoomPrices $price
    ): HotelAccommodation;
}
