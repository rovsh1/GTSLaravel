<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Application\UseCase;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Application\Service\EventDtoFactory;
use Module\Booking\EventSourcing\Domain\Service\HistoryStorageInterface;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatusHistory implements UseCaseInterface
{
    public function __construct(
        private readonly HistoryStorageInterface $historyStorage,
        private readonly EventDtoFactory $eventDtoFactory
    ) {}

    /**
     * @param int $id
     * @return array<int, EventDto>
     */
    public function execute(int $id): array
    {
        $statusEvents = $this->historyStorage->getStatusHistory($id);

        return $statusEvents->map(fn(BookingHistory $history) => $this->eventDtoFactory->build($history))->all();
    }
}
