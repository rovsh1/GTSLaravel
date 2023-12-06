<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;

class EventDtoMapper
{
    public function __construct(
        private readonly StatusDtoFactory $statusDtoFactory,
        private readonly RequestDtoFactory $requestDtoFactory,
        private readonly DefaultDtoFactory $defaultDtoFactory,
    ) {}

    public function toDto(BookingHistory $history): EventDto
    {
        return $this->chooseFactory($history->group)->build($history);
    }

    private function chooseFactory(EventGroupEnum $group): DtoFactoryInterface
    {
        return match ($group) {
            EventGroupEnum::STATUS_UPDATED => $this->statusDtoFactory,
            EventGroupEnum::REQUEST_SENT => $this->requestDtoFactory,
            default => $this->defaultDtoFactory
        };
    }
}