<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\Repository;

use Module\Booking\Domain\HotelBooking\Entity\RoomBooking;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBookingCollection;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;

interface RoomBookingRepositoryInterface
{
    public function find(int $id): ?RoomBooking;

    public function get(int $bookingId): RoomBookingCollection;

    public function store(RoomBooking $booking): bool;

    public function delete(int $id): bool;

    public function create(
        BookingId $bookingId,
        RoomInfo $roomInfo,
        RoomBookingDetails $details,
        RoomPrice $price
    ): RoomBooking;
}
