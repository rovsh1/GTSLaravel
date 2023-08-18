<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Repository;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;

interface RoomBookingRepositoryInterface
{
    public function find(int $id): ?RoomBooking;

    public function get(int $bookingId): RoomBookingCollection;

    public function store(RoomBooking $booking): bool;

    public function delete(int $id): bool;

    public function create(
        BookingId $bookingId,
        RoomBookingStatusEnum $status,
        RoomInfo $roomInfo,
        GuestCollection $guests,
        RoomBookingDetails $details,
        RoomPrice $price
    ): RoomBooking;
}
