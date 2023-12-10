<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;

class EventDtoMapper
{
    public function __construct(
        private readonly StatusDescriptor $statusDescriptor,
        private readonly RequestDescriptor $requestDescriptor,
        private readonly DefaultDescriptor $defaultDescriptor,
    ) {}

    public function toDto(BookingHistory $history): EventDto
    {
        $descriptor = $this->makeDescriptor($history->group);
        $description = $descriptor->build($history);

        return new EventDto(
            event: $history->payload['@event'],
            description: $description->text,
            color: $description->color,
            payload: $history->payload,
            context: $history->context,
            createdAt: $history->created_at
        );
    }

    private function makeDescriptor(EventGroupEnum $group): DescriptorInterface
    {
        return match ($group) {
            EventGroupEnum::STATUS_UPDATED => $this->statusDescriptor,
//            EventGroupEnum::PRICE_CHANGED => $this->statusDtoFactory,
            EventGroupEnum::REQUEST_SENT => $this->requestDescriptor,
            default => $this->defaultDescriptor
        };
    }
}