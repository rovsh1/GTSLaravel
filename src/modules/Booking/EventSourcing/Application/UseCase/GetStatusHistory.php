<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Application\UseCase;

use Module\Booking\EventSourcing\Application\Dto\StatusEventDto;
use Module\Booking\EventSourcing\Domain\Service\HistoryStorageInterface;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Module\Booking\Shared\Application\Factory\BookingStatusDtoFactory;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;

class GetStatusHistory implements UseCaseInterface
{
    public function __construct(
        private readonly HistoryStorageInterface $historyStorage,
        private readonly BookingStatusDtoFactory $statusDtoFactory
    ) {
    }

    /**
     * @param int $id
     * @return array<int, StatusEventDto>
     */
    public function execute(int $id): array
    {
        $statusEvents = $this->historyStorage->getStatusHistory($id);
        $statusesSettings = $this->statusDtoFactory->statuses();
        $statusColorsIndexedByStatusId = collect($statusesSettings)->keyBy('id')->map->color;

        return $statusEvents->map(fn(BookingHistory $changesLog) => new StatusEventDto(
            $this->getEvent($changesLog->payload['status']),
            $statusColorsIndexedByStatusId[$changesLog->payload['status']] ?? null,
            $changesLog->payload,
            $changesLog->context['source'] ?? null,
            $changesLog->context['administrator']['name'] ?? null,
            $changesLog->created_at->toImmutable()
        ))->all();
    }

    private function getEvent(int $status): string
    {
        return BookingStatusEnum::from($status)->name;
    }
}
