<?php

namespace Module\Booking\HotelBooking\Domain\Service\RoomUpdater\Validator;

use Module\Booking\Common\Domain\Adapter\ClientAdapterInterface;
use Module\Booking\HotelBooking\Domain\Exception\InvalidRoomResidency;
use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\UpdateDataHelper;
use Module\Shared\Enum\Client\ResidencyEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class ClientResidencyValidator implements ValidatorInterface
{
    public function __construct(
        private readonly ClientAdapterInterface $clientAdapter
    ) {}

    public function validate(UpdateDataHelper $dataHelper): void
    {
        $client = $this->clientAdapter->find($dataHelper->booking->id()->value());
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
