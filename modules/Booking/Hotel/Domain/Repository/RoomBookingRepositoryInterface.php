<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Repository;

use Module\Booking\Hotel\Domain\Entity\RoomBooking;
use Module\Booking\Hotel\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Booking\Hotel\Domain\ValueObject\RoomPrice;

interface RoomBookingRepositoryInterface
{
    public function find(int $id): ?RoomBooking;

    public function get(int $bookingId): RoomBookingCollection;

    public function store(RoomBooking $booking): bool;

    public function delete(int $id): bool;

    public function create(
        int $bookingId,
        RoomBookingStatusEnum $status,
        RoomInfo $roomInfo,
        GuestCollection $guests,
        RoomBookingDetails $details,
        RoomPrice $price
    ): RoomBooking;
}
