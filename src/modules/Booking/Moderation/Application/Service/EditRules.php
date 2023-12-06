<?php

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Enum\StatusEnum;
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
            StatusEnum::CREATED,
            StatusEnum::PROCESSING,
            StatusEnum::WAITING_PROCESSING,
            StatusEnum::NOT_CONFIRMED,
        ]);
    }

    public function canEditExternalNumber(): bool
    {
        return !$this->isOtherService() && $this->booking->status() === StatusEnum::CONFIRMED;
    }

    public function canChangeRoomPrice(): bool
    {
        return $this->isHotelBooking()
            && in_array($this->booking->status(), [
                StatusEnum::CREATED,
                StatusEnum::PROCESSING,
                StatusEnum::WAITING_PROCESSING,
                StatusEnum::NOT_CONFIRMED,
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
