<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Shared\Dto\FileDto;

abstract class AbstractDescriptor
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

    protected function fileLink(?FileDto $fileDto): string
    {
        return $fileDto
            ? '<a href="' . $fileDto->url . ' " class="ui-attachment-link" target="_blank">скачать</a>'
            : '<i>&lt;Файл недоступен&gt;</i>';
    }
}