<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Sdk\Booking\Enum\StatusEnum;

class StatusDescriptor extends AbstractDescriptor implements DescriptorInterface
{
    public function __construct(
        private readonly BookingStatusStorageInterface $bookingStatusStorage,
    ) {}

    public function build(BookingHistory $history): DescriptionDto
    {
        $statusEnum = StatusEnum::from($history->payload['status']);

        return new DescriptionDto(
            $this->bookingStatusStorage->getName($statusEnum),
            $this->bookingStatusStorage->getColor($statusEnum),
        );
    }
}
