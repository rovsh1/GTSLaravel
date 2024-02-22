<?php

namespace Pkg\Booking\EventSourcing\Application\Service;

use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Pkg\Booking\EventSourcing\Application\Dto\EventDto;
use Pkg\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Pkg\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Booking\Enum\StatusEnum;

class EventDtoMapper
{
    public function __construct(
        private readonly BookingStatusStorageInterface $bookingStatusStorage,
    ) {}

    public function toDto(BookingHistory $history): EventDto
    {
        return new EventDto(
            event: $history->field,
            description: $history->description,
            color: $this->getColor($history),
            payload: [
                'before' => $history->before,
                'after' => $history->after,
            ],
            context: $history->context,
            createdAt: $history->created_at
        );
    }

    private function getColor(BookingHistory $history): ?string
    {
        if ($history->group === EventGroupEnum::STATUS_UPDATED) {
            return $this->bookingStatusStorage->getColor(StatusEnum::from($history->after));
        } else {
            return null;
        }
    }
}