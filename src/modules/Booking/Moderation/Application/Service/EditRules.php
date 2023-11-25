<?php

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;

final class EditRules
{
    protected Booking $booking;

    public function booking(Booking $booking): void
    {
        $this->booking = $booking;
    }

    public function isEditable(): bool
    {
        return in_array($this->booking->status(), [
            BookingStatusEnum::CREATED,
            BookingStatusEnum::PROCESSING,
            BookingStatusEnum::WAITING_PROCESSING,
            BookingStatusEnum::NOT_CONFIRMED,
        ]);
    }

    public function canEditExternalNumber(): bool
    {
        return !$this->isOtherService() && $this->booking->status() === BookingStatusEnum::CONFIRMED;
    }

    public function canChangeRoomPrice(): bool
    {
        return $this->isHotelBooking()
            && in_array($this->booking->status(), [
                BookingStatusEnum::CREATED,
                BookingStatusEnum::PROCESSING,
                BookingStatusEnum::WAITING_PROCESSING,
                BookingStatusEnum::NOT_CONFIRMED,
            ]) && !$this->booking->prices()->clientPrice()->manualValue();
    }

    public function canCopy(): bool
    {
        return $this->booking->isCancelled();
    }

    private function isHotelBooking(): bool
    {
        return $this->booking->serviceType() === ServiceTypeEnum::HOTEL_BOOKING;
    }

    private function isOtherService(): bool
    {
        return $this->booking->serviceType() === ServiceTypeEnum::OTHER_SERVICE;
    }
}
