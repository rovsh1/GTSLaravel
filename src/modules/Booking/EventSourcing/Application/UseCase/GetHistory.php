<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Application\UseCase;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Application\Service\HistoryBuilder\EventDtoMapper;
use Module\Booking\EventSourcing\Domain\Service\HistoryStorageInterface;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetHistory implements UseCaseInterface
{
    public function __construct(
        private readonly HistoryStorageInterface $historyStorage,
        private readonly EventDtoMapper $eventDtoMapper
    ) {}

    /**
     * @param int $id
     * @return array<int, EventDto>
     */
    public function execute(int $id): array
    {
        $statusEvents = $this->historyStorage->getHistory($id);

        return $statusEvents->map(fn(BookingHistory $history) => $this->eventDtoMapper->toDto($history))->all();
    }
}
