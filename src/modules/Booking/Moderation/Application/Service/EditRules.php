<?php

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\StatusTransitionsFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;

final class EditRules
{
    protected Booking $booking;

    private Order $order;

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly StatusTransitionsFactory $statusTransitionsFactory,
    ) {}

    public function booking(Booking $booking): void
    {
        $this->booking = $booking;
        $this->order = $this->orderRepository->findOrFail($booking->orderId());
    }

    public function isEditable(): bool
    {
        return $this->order->inModeration() && in_array($this->booking->status()->value(), [
                StatusEnum::CREATED,
                StatusEnum::PROCESSING,
                StatusEnum::WAITING_PROCESSING,
                StatusEnum::NOT_CONFIRMED,
            ]);
    }

    public function canEditExternalNumber(): bool
    {
        return !$this->isOtherService() && $this->booking->status()->value() === StatusEnum::CONFIRMED;
    }

    public function canChangeRoomPrice(): bool
    {
        return $this->isHotelBooking() && in_array($this->booking->status()->value(), [
                StatusEnum::CREATED,
                StatusEnum::PROCESSING,
                StatusEnum::WAITING_PROCESSING,
                StatusEnum::NOT_CONFIRMED,
                StatusEnum::CANCELLED_NO_FEE,
                StatusEnum::CANCELLED_FEE,
            ]) && !$this->booking->prices()->clientPrice()->manualValue();
    }

    public function canChangeCarBidPrice(): bool
    {
        return $this->isTransferBooking() && in_array($this->booking->status()->value(), [
                StatusEnum::CREATED,
                StatusEnum::PROCESSING,
                StatusEnum::WAITING_PROCESSING,
                StatusEnum::NOT_CONFIRMED,
                StatusEnum::CANCELLED_NO_FEE,
                StatusEnum::CANCELLED_FEE,
            ]) && !$this->booking->prices()->clientPrice()->manualValue();
    }

    public function canCopy(): bool
    {
        return $this->booking->isCancelled();
    }

    public function canEditSupplierPrice(): bool
    {
        return true;
    }

    public function canEditClientPrice(): bool
    {
        return $this->order->inModeration();
    }

    /**
     * @return StatusEnum[]
     */
    public function getAvailableStatusTransitions(): array
    {
        $statusTransitions = $this->statusTransitionsFactory->build($this->booking->serviceType());
        if (!$this->order->inModeration() && !$this->booking->isWaitingCancellation() && !($this->isOtherService() && $this->booking->isConfirmed())) {
            return [];
        }

        return $statusTransitions->getAvailableTransitions($this->booking->status()->value());
    }

    private function isHotelBooking(): bool
    {
        return $this->booking->serviceType() === ServiceTypeEnum::HOTEL_BOOKING;
    }

    private function isTransferBooking(): bool
    {
        return $this->booking->serviceType()->isTransferService();
    }

    private function isOtherService(): bool
    {
        return $this->booking->serviceType() === ServiceTypeEnum::OTHER_SERVICE;
    }
}
