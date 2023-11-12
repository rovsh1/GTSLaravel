<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Application\UseCase;

use Module\Booking\EventSourcing\Application\Dto\StatusEventDto;
use Module\Booking\EventSourcing\Domain\Repository\BookingLogRepositoryInterface;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingEventLog;
use Module\Booking\Shared\Application\Factory\BookingStatusDtoFactory;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatusHistory implements UseCaseInterface
{
    public function __construct(
        private readonly BookingLogRepositoryInterface $changesLogRepository,
        private readonly BookingStatusDtoFactory $statusDtoFactory
    ) {
    }

    /**
     * @param int $id
     * @return array<int, StatusEventDto>
     */
    public function execute(int $id): array
    {
        $statusEvents = $this->changesLogRepository->getStatusHistory($id);
        $statusesSettings = $this->statusDtoFactory->statuses();
        $statusColorsIndexedByStatusId = collect($statusesSettings)->keyBy('id')->map->color;

        return $statusEvents->map(fn(BookingEventLog $changesLog) => new StatusEventDto(
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
