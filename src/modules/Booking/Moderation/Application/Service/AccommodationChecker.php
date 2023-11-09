<?php

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Moderation\Application\Exception\InvalidRoomClientResidencyException;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\Adapter\ClientAdapterInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class AccommodationChecker
{
    public function __construct(
        private readonly ClientAdapterInterface $clientAdapter,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
    }

    public function validate(
        int $orderId,
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount
    ): void {
        //@todo check hotel for booking availability (has prices)

        $this->validateClient($orderId, $isResident);
    }

    private function validateClient(int $orderId, bool $isResident): void
    {
        $order = $this->orderRepository->findOrFail(new OrderId($orderId));
        $client = $this->clientAdapter->find($order->clientId()->value());
        if ($client === null) {
            throw new EntityNotFoundException('Client not found');
        }

        if ($client->isLegal() && !$client->residency->hasResidencyFlag($isResident)) {
            throw new InvalidRoomClientResidencyException();
        }
    }
}