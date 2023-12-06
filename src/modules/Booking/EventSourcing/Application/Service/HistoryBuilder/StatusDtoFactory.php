<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Module\Booking\Shared\Application\Factory\BookingStatusDtoFactory;
use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;

class StatusDtoFactory extends AbstractDtoFactory implements DtoFactoryInterface
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

    public function build(BookingHistory $history): EventDto
    {
        return $this->wrap(
            $history,
            $this->bookingStatusStorage->getName(BookingStatusEnum::from($history->payload['status'])),
            $this->statusColors[$history->payload['status']] ?? null
        );
    }
}