<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository;

use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationCollection;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationDetails;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Booking\ValueObject\HotelBooking\RoomInfo;
use Sdk\Booking\ValueObject\HotelBooking\RoomPrices;

interface AccommodationRepositoryInterface
{
    /**
     * @return HotelAccommodation[]
     */
    public function get(): array;

    public function find(AccommodationId $id): ?HotelAccommodation;

    public function findOrFail(AccommodationId $id): HotelAccommodation;

    public function getByBookingId(BookingId $bookingId): AccommodationCollection;

    public function store(HotelAccommodation $accommodation): void;

    public function delete(AccommodationId $id): void;

    public function create(
        BookingId $bookingId,
        RoomInfo $roomInfo,
        AccommodationDetails $details,
        RoomPrices $price
    ): HotelAccommodation;
}
