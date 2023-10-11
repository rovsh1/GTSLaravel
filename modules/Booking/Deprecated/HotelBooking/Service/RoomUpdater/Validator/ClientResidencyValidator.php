<?php

namespace Module\Booking\Deprecated\HotelBooking\Service\RoomUpdater\Validator;

use Module\Booking\Deprecated\HotelBooking\Exception\InvalidRoomResidency;
use Module\Booking\Deprecated\HotelBooking\Service\RoomUpdater\UpdateDataHelper;
use Module\Booking\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Domain\Shared\Adapter\ClientAdapterInterface;
use Module\Shared\Enum\Client\ResidencyEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class ClientResidencyValidator implements ValidatorInterface
{
    public function __construct(
        private readonly ClientAdapterInterface $clientAdapter,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {}

    public function validate(UpdateDataHelper $dataHelper): void
    {
        $order = $this->orderRepository->findOrFail($dataHelper->booking->orderId()->value());
        $client = $this->clientAdapter->find($order->clientId()->value());
        if ($client === null) {
            throw new EntityNotFoundException('Client not found');
        }

        if ($client->isLegal() && !$this->isResidencyValid($dataHelper->details->isResident(), $client->residency)) {
            throw new InvalidRoomResidency('Client doesn\'t support chosen residency');
        }
    }

    private function isResidencyValid(bool $roomIsResident, ResidencyEnum $clientResidency): bool
    {
        if ($roomIsResident && !in_array($clientResidency, [ResidencyEnum::RESIDENT, ResidencyEnum::ALL])) {
            return false;
        }

        if (!$roomIsResident && !in_array($clientResidency, [ResidencyEnum::NONRESIDENT, ResidencyEnum::ALL])) {
            return false;
        }

        return true;
    }
}
