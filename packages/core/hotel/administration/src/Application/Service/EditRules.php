<?php

namespace Pkg\Hotel\Administration\Application\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Pkg\Hotel\Administration\Application\Service\StatusRules\DefaultTransitions;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\Enum\StatusEnum;

final class EditRules
{
    protected Booking $booking;

    protected HotelBooking $details;

    private Order $order;

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly DetailsRepositoryInterface $detailsRepository,
    ) {}

    public function booking(Booking $booking): void
    {
        $this->booking = $booking;
        $this->order = $this->orderRepository->findOrFail($booking->orderId());
        $this->details = $this->detailsRepository->findOrFail($booking->id());
    }

    public function canEditExternalNumber(): bool
    {
        return in_array($this->booking->status()->value(), [
            StatusEnum::CONFIRMED,
            StatusEnum::WAITING_CONFIRMATION,
        ]);
    }

    public function canSetNoCheckIn(): bool
    {
        $checkInTimestamp = $this->details->bookingPeriod()->dateFrom()->getTimestamp();
        $isCheckInDateExpired = $checkInTimestamp >= time();
        $isLessThan72HorusAfterCheckIn = $checkInTimestamp < time() + 60 * 60 * 72;

        return $this->booking->status() === StatusEnum::CONFIRMED
            && $isCheckInDateExpired
            && $isLessThan72HorusAfterCheckIn;
    }

    /**
     * @return StatusEnum[]
     */
    public function getAvailableStatusTransitions(): array
    {
        $statusTransitions = new DefaultTransitions();
        if (!$this->order->inModeration()) {
            return [];
        }

        return $statusTransitions->getAvailableTransitions($this->booking->status()->value());
    }
}
