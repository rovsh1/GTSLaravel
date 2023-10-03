<?php

namespace Module\Booking\Domain\HotelBooking\Service\QuotaManager\ProcessingMethod;

use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\ProcessingMethod\Quota\QuotaReservationManager;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\QuotaProcessingMethodInterface;
use Module\Booking\Domain\Shared\Service\StatusRules\AdministratorRules;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;

class Quota implements QuotaProcessingMethodInterface
{
    public function __construct(
        private readonly AdministratorRules $administratorRules,
        private readonly QuotaReservationManager $quotaReservationManager,
    ) {}

    public function process(HotelBooking $booking): void
    {
        if ($this->isEditableBooking($booking)) {
            $this->quotaReservationManager->reserve($booking);
        } elseif ($this->isBookingConfirmed($booking)) {
            $this->quotaReservationManager->book($booking);
        } elseif ($this->isBookingCancelled($booking)) {
            $this->quotaReservationManager->cancel($booking);
        }
    }

    private function isEditableBooking(HotelBooking $booking): bool
    {
        return $this->administratorRules->isEditableStatus($booking->status());
    }

    private function isBookingConfirmed(HotelBooking $booking): bool
    {
        //@todo надо перенести в рулзы
        return $booking->status() === BookingStatusEnum::CONFIRMED;
    }

    private function isBookingCancelled(HotelBooking $booking): bool
    {
        return $this->administratorRules->isCancelledStatus($booking->status())
            || $this->administratorRules->isDeletedStatus($booking->status());
    }
}
