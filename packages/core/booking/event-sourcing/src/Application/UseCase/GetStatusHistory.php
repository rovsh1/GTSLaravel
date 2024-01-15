<?php

declare(strict_types=1);

namespace Pkg\Booking\EventSourcing\Application\UseCase;

use Pkg\Booking\EventSourcing\Application\Dto\EventDto;
use Pkg\Booking\EventSourcing\Application\Service\EventDtoMapper;
use Pkg\Booking\EventSourcing\Domain\Service\HistoryStorageInterface;
use Pkg\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatusHistory implements UseCaseInterface
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
        $statusEvents = $this->historyStorage->getStatusHistory($id);

        return $statusEvents->map(fn(BookingHistory $history) => $this->eventDtoMapper->toDto($history))->all();
    }
}
