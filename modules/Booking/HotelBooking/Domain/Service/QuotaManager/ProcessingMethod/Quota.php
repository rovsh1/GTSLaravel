<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod;

use Module\Booking\Common\Domain\Service\StatusRules\AdministratorRules;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod\Quota\QuotaReservationManager;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\QuotaProcessingMethodInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Quota implements QuotaProcessingMethodInterface
{
    public function __construct(
        private readonly AdministratorRules $administratorRules,
        private readonly QuotaReservationManager $quotaReservationManager,
        private readonly BookingRepositoryInterface $bookingRepository,
    ) {}

    public function process(BookingId $bookingId): void
    {
        $booking = $this->bookingRepository->find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        if ($this->isEditableBooking($booking)) {
            $this->quotaReservationManager->reserve($booking);
        } elseif ($this->isBookingConfirmed($booking)) {
            $this->quotaReservationManager->book($booking);
        } elseif ($this->isBookingCancelled($booking)) {
            $this->quotaReservationManager->cancel($booking);
        }
    }

    public function ensureRoomAvailable(Booking $booking, int $roomId): void
    {
        $this->quotaReservationManager->ensureRoomAvailable($booking, $roomId);
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
        //@todo добавить условние если бронь удалена
        return $this->administratorRules->isCancelledStatus($booking->status());
    }
}
