<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Module\Booking\Shared\Application\Factory\BookingStatusDtoFactory;
use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Sdk\Booking\Enum\StatusEnum;

class StatusDescriptor extends AbstractDescriptor implements DescriptorInterface
{
    private readonly array $statusColors;

    public function __construct(
        private readonly BookingStatusDtoFactory $statusDtoFactory,
        private readonly BookingStatusStorageInterface $bookingStatusStorage,
    ) {
        $colors = [];
        $statusesSettings = $this->statusDtoFactory->statuses();
        foreach ($statusesSettings as $statusDto) {
            $colors[$statusDto->id] = $statusDto->color;
        }
        $this->statusColors = $colors;
    }

    public function build(BookingHistory $history): DescriptionDto
    {
        return new DescriptionDto(
            $this->bookingStatusStorage->getName(StatusEnum::from($history->payload['status'])),
            $this->statusColors[$history->payload['status']] ?? null
        );
    }
}