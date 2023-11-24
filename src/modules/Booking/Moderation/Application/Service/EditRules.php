<?php

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\ServiceTypeEnum;

final class EditRules
{
    protected Booking $booking;

    public function booking(Booking $booking): void
    {
        $this->booking = $booking;
    }

//    /**
//     * @return BookingStatusEnum[]
//     */
//    public static function getCompletedStatuses(): array
//    {
//        return [
//            BookingStatusEnum::CONFIRMED,
//            BookingStatusEnum::CANCELLED_FEE,
//            BookingStatusEnum::CANCELLED_NO_FEE
//        ];
//    }

    public function isEditable(): bool
    {
        return in_array($this->booking->status(), [
            BookingStatusEnum::CREATED,
            BookingStatusEnum::PROCESSING,
        ]);
        //@todo зачем тут реквесты?
        //|| ($this->requestRules->isRequestableStatus($status) && $status !== BookingStatusEnum::CONFIRMED);
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
