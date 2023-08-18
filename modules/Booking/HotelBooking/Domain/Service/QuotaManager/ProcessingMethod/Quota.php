<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod;

use Module\Booking\Common\Domain\Service\StatusRules\AdministratorRules;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod\Quota\QuotaReservationManager;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\QuotaProcessingMethodInterface;

class Quota implements QuotaProcessingMethodInterface
{
    public function __construct(
        private readonly AdministratorRules $administratorRules,
        private readonly QuotaReservationManager $quotaReservationManager,
    ) {}

    public function process(Booking $booking): void
    {
        if ($this->isEditableBooking($booking)) {
            $this->quotaReservationManager->reserve($booking);
        } elseif ($this->isBookingConfirmed($booking)) {
            $this->quotaReservationManager->book($booking);
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
