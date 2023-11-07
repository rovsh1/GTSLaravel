<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository;

use Module\Booking\Shared\Domain\Booking\Entity\HotelRoomBooking;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingDetails;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingIdCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPrices;

interface RoomBookingRepositoryInterface
{
    public function find(RoomBookingId $id): ?HotelRoomBooking;

    public function findOrFail(RoomBookingId $id): HotelRoomBooking;

    public function get(RoomBookingIdCollection $roomBookingIds): RoomBookingCollection;

    public function store(HotelRoomBooking $booking): bool;

    public function delete(RoomBookingId $id): bool;

    public function create(
        BookingId $bookingId,
        RoomInfo $roomInfo,
        RoomBookingDetails $details,
        RoomPrices $price
    ): HotelRoomBooking;
}
