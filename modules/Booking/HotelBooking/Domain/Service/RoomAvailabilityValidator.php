<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service;

use Module\Booking\Common\Domain\Adapter\ClientAdapterInterface;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Exception\InvalidRoomResidency;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\QuotaProcessingMethodFactory;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Module\Shared\Enum\Client\ResidencyEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class RoomAvailabilityValidator
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ClientAdapterInterface $clientAdapter,
        private readonly QuotaProcessingMethodFactory $quotaProcessingMethodFactory,
    ) {}

    public function ensureRoomAvailable(Booking $booking, int $roomId, bool $isResident): void
    {
        $clientResidency = $this->getClientResidency($booking->orderId());
        if ($isResident && !in_array($clientResidency, [ResidencyEnum::RESIDENT, ResidencyEnum::ALL])) {
            throw new InvalidRoomResidency('Client doesn\'t support resident prices');
        }

        if (!$isResident && !in_array($clientResidency, [ResidencyEnum::NONRESIDENT, ResidencyEnum::ALL])) {
            throw new InvalidRoomResidency('Client doesn\'t support non resident prices');
        }
        $quotaProcessingMethod = $this->quotaProcessingMethodFactory->build($booking->quotaProcessingMethod());
        $quotaProcessingMethod->ensureRoomAvailable($booking, $roomId);
    }

    private function getClientResidency(OrderId $orderId): ResidencyEnum
    {
        $order = $this->orderRepository->find($orderId->value());
        if ($order === null) {
            throw new EntityNotFoundException('Order not found');
        }
        $client = $this->clientAdapter->find($order->clientId()->value());
        if ($client === null) {
            throw new EntityNotFoundException('Client not found');
        }

        return $client->residency;
    }
}
