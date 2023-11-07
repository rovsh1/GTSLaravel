<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Domain\Shared\Event\BookingCreated;
use Module\Booking\Domain\Shared\Event\Status\BookingCancelled;
use Module\Booking\Domain\Shared\Event\Status\BookingCancelledFee;
use Module\Booking\Domain\Shared\Event\Status\BookingCancelledNoFee;
use Module\Booking\Domain\Shared\Event\Status\BookingConfirmed;
use Module\Booking\Domain\Shared\Event\Status\BookingNotConfirmed;
use Module\Booking\Domain\Shared\Event\Status\BookingProcessing;
use Module\Booking\Domain\Shared\Event\Status\BookingWaitingCancellation;
use Module\Booking\Domain\Shared\Event\Status\BookingWaitingConfirmation;
use Module\Booking\Domain\Shared\Event\Status\BookingWaitingProcessing;
use Module\Booking\Domain\Shared\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Infrastructure\Models\BookingChangesLog;
use Module\Booking\Moderation\Application\Dto\StatusEventDto;
use Module\Booking\Moderation\Application\Factory\BookingStatusDtoFactory;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatusHistory implements UseCaseInterface
{
    public function __construct(
        private readonly BookingChangesLogRepositoryInterface $changesLogRepository,
        private readonly BookingStatusDtoFactory $statusDtoFactory
    ) {}

    /**
     * @param int $id
     * @return array<int, StatusEventDto>
     */
    public function execute(int $id): array
    {
        $statusEvents = $this->changesLogRepository->getStatusHistory($id);
        $statusesSettings = $this->statusDtoFactory->statuses();
        $statusColorsIndexedByStatusId = collect($statusesSettings)->keyBy('id')->map->color;

        return $statusEvents->map(fn(BookingChangesLog $changesLog) => new StatusEventDto(
            __($changesLog->event),
            $statusColorsIndexedByStatusId[$this->getEventStatus($changesLog->event)] ?? null,
            $changesLog->payload,
            $changesLog->context['source'] ?? null,
            $changesLog->context['administrator']['name'] ?? null,
            $changesLog->created_at->toImmutable()
        ))->all();
    }

    private function getEventStatus(string $event): int
    {
        return match ($event) {
            BookingCreated::class => BookingStatusEnum::CREATED->value,
            BookingProcessing::class => BookingStatusEnum::PROCESSING->value,
            BookingCancelled::class => BookingStatusEnum::CANCELLED->value,
            BookingConfirmed::class => BookingStatusEnum::CONFIRMED->value,
            BookingNotConfirmed::class => BookingStatusEnum::NOT_CONFIRMED->value,
            BookingCancelledNoFee::class => BookingStatusEnum::CANCELLED_NO_FEE->value,
            BookingCancelledFee::class => BookingStatusEnum::CANCELLED_FEE->value,
            BookingWaitingConfirmation::class => BookingStatusEnum::WAITING_CONFIRMATION->value,
            BookingWaitingCancellation::class => BookingStatusEnum::WAITING_CANCELLATION->value,
            BookingWaitingProcessing::class => BookingStatusEnum::WAITING_PROCESSING->value,
        };
    }
}
