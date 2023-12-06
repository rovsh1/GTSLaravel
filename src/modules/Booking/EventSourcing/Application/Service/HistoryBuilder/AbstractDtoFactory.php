<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;

abstract class AbstractDtoFactory
{
    protected function wrap(BookingHistory $history, string $description, string $color = null): EventDto
    {
        return new EventDto(
            event: $history->payload['@event'],
            description: $description,
            color: $color,
            payload: $history->payload,
            context: $history->context,
            createdAt: $history->created_at
        );
    }
}