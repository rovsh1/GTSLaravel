<?php

namespace Module\Booking\HotelBooking\Domain\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Hotel\Application\Response\HotelDto;
use Module\Hotel\Application\Response\MarkupSettingsDto;
use Module\Hotel\Application\Response\RoomMarkupsDto;

interface HotelQuotaAdapterInterface
{
    public function accept(BookingId $id): void;

    public function reserve(RoomBookingId $roomId): void;

    public function cancel(RoomBookingId $roomId): void;
}
