<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;

final class RequestingRules
{
    private Booking $booking;

    private Order $order;

    /**
     * @var array<int, StatusEnum> $requestableStatuses
     */
    private const REQUESTABLE_STATUSES = [
        StatusEnum::PROCESSING,
        StatusEnum::CONFIRMED,
        StatusEnum::WAITING_PROCESSING,
        StatusEnum::NOT_CONFIRMED,
    ];

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
    ) {}

    public function booking(Booking $booking): void
    {
        $this->booking = $booking;
        $this->order = $this->orderRepository->findOrFail($booking->orderId());
    }

    /**
     * @return StatusEnum[]
     */
    public static function getRequestableStatuses(): array
    {
        return self::REQUESTABLE_STATUSES;
    }

    public function isBookingRequestable(): bool
    {
        return !$this->isOtherService()
            && $this->order->inModeration()
            && in_array($this->booking->status(), self::REQUESTABLE_STATUSES);
    }

    public function canSendCancellationRequest(): bool
    {
        return $this->isBookingRequestable() && $this->booking->status() === StatusEnum::CONFIRMED;
    }

    public function canSendBookingRequest(): bool
    {
        return $this->isBookingRequestable() && $this->booking->status() === StatusEnum::PROCESSING;
    }

    public function canSendChangeRequest(): bool
    {
        return $this->isBookingRequestable() && in_array($this->booking->status(), [
                StatusEnum::WAITING_PROCESSING,
                StatusEnum::NOT_CONFIRMED
            ]);
    }

    public function getRequestType(): ?RequestTypeEnum
    {
        if ($this->canSendBookingRequest()) {
            return RequestTypeEnum::BOOKING;
        } elseif ($this->canSendCancellationRequest()) {
            return RequestTypeEnum::CANCEL;
        } elseif ($this->canSendChangeRequest()) {
            return RequestTypeEnum::CHANGE;
        } else {
            return null;
        }
    }

    private function isOtherService(): bool
    {
        return $this->booking->serviceType() === ServiceTypeEnum::OTHER_SERVICE;
    }
}
