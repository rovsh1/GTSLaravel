<?php

namespace Module\Booking\EventSourcing\Application\Service;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Module\Booking\Shared\Application\Factory\BookingStatusDtoFactory;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;

class EventDtoFactory
{
    private readonly array $statusColors;

    public function __construct(
        private readonly BookingStatusDtoFactory $statusDtoFactory
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
        if ($history->group === EventGroupEnum::STATUS_UPDATED) {
            return $this->buildStatusDto($history);
        } else {
            return new EventDto(
                event: $history->payload['@event'],
                description: $history->payload['@event'],
                color: null,
                payload: $history->payload,
                context: $history->context,
                createdAt: $history->created_at
            );
        }
    }

    private function buildStatusDto(BookingHistory $history): EventDto
    {
        return new EventDto(
            event: $history->payload['@event'],
            description: $this->getEvent($history->payload['status']),
            color: $this->statusColors[$history->payload['status']] ?? null,
            payload: $history->payload,
            context: $history->context,
            createdAt: $history->created_at
        );
    }

    private function getEvent(int $status): string
    {
        return BookingStatusEnum::from($status)->name;
    }
}