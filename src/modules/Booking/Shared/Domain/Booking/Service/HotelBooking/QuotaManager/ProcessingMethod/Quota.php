<?php

namespace Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod;

use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\AdministratorRules;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod\Quota\QuotaReservationManager;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\QuotaProcessingMethodInterface;
use Module\Shared\Enum\Booking\BookingStatusEnum;

class Quota implements QuotaProcessingMethodInterface
{
    public function __construct(
        private readonly AdministratorRules $administratorRules,
        private readonly QuotaReservationManager $quotaReservationManager,
    ) {
    }

    public function process(Booking $booking, HotelBooking $details): void
    {
        if ($this->isEditableBooking($booking)) {
            $this->quotaReservationManager->reserve($booking, $details);
        } elseif ($this->isBookingConfirmed($booking)) {
            $this->quotaReservationManager->book($booking, $details);
        } elseif ($this->isBookingCancelled($booking)) {
            $this->quotaReservationManager->cancel($booking);
        }
    }

    private function isEditableBooking(Booking $booking): bool
    {
        return $this->administratorRules->isEditableStatus($booking->status());
    }

    private function isBookingConfirmed(Booking $booking): bool
    {
        //@todo надо перенести в рулзы
        return $booking->status() === BookingStatusEnum::CONFIRMED;
    }

    private function isBookingCancelled(Booking $booking): bool
    {
        return $this->administratorRules->isCancelledStatus($booking->status())
            || $this->administratorRules->isDeletedStatus($booking->status());
    }
}