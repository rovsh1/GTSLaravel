<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Entity;

use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingDetails;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;

final class HotelRoomBooking
{
    use Concerns\HasGuestIdCollectionTrait;

    public function __construct(
        private readonly RoomBookingId $id,
        private readonly BookingId $bookingId,
        private readonly RoomInfo $roomInfo,
        private GuestIdCollection $guestIds,
        private RoomBookingDetails $details,
        private RoomPrices $prices,
    ) {}

    public function id(): RoomBookingId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function roomInfo(): RoomInfo
    {
        return $this->roomInfo;
    }

    public function details(): RoomBookingDetails
    {
        return $this->details;
    }

    public function updateDetails(RoomBookingDetails $details): void
    {
        $this->details = $details;
    }

    public function prices(): RoomPrices
    {
        return $this->prices;
    }

    public function updatePrices(RoomPrices $prices): void
    {
        $this->prices = $prices;
    }
}
