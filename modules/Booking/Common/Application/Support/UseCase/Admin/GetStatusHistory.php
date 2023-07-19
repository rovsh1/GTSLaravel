<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin;

use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Event\BookingCreated;
use Module\Booking\Common\Domain\Event\Status\BookingCancelled;
use Module\Booking\Common\Domain\Event\Status\BookingCancelledFee;
use Module\Booking\Common\Domain\Event\Status\BookingCancelledNoFee;
use Module\Booking\Common\Domain\Event\Status\BookingConfirmed;
use Module\Booking\Common\Domain\Event\Status\BookingInvoiced;
use Module\Booking\Common\Domain\Event\Status\BookingNotConfirmed;
use Module\Booking\Common\Domain\Event\Status\BookingPaid;
use Module\Booking\Common\Domain\Event\Status\BookingPartiallyPaid;
use Module\Booking\Common\Domain\Event\Status\BookingProcessing;
use Module\Booking\Common\Domain\Event\Status\BookingRefundFee;
use Module\Booking\Common\Domain\Event\Status\BookingRefundNoFee;
use Module\Booking\Common\Domain\Event\Status\BookingWaitingCancellation;
use Module\Booking\Common\Domain\Event\Status\BookingWaitingConfirmation;
use Module\Booking\Common\Domain\Event\Status\BookingWaitingProcessing;
use Module\Booking\Common\Domain\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Infrastructure\Models\BookingChangesLog;
use Module\Booking\Hotel\Application\Dto\StatusEventDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatusHistory implements UseCaseInterface
{
    public function __construct(
        private readonly BookingChangesLogRepositoryInterface $changesLogRepository,
        private readonly StatusStorage $statusStorage
    ) {}

    /**
     * @param int $id
     * @return array<int, StatusEventDto>
     */
    public function execute(int $id): array
    {
        $statusEvents = $this->changesLogRepository->getStatusHistory($id);
        $statusesSettings = $this->statusStorage->statuses();
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
            BookingInvoiced::class => BookingStatusEnum::INVOICED->value,
            BookingPaid::class => BookingStatusEnum::PAID->value,
            BookingPartiallyPaid::class => BookingStatusEnum::PARTIALLY_PAID->value,
            BookingCancelledNoFee::class => BookingStatusEnum::CANCELLED_NO_FEE->value,
            BookingCancelledFee::class => BookingStatusEnum::CANCELLED_FEE->value,
            BookingRefundNoFee::class => BookingStatusEnum::REFUND_NO_FEE->value,
            BookingRefundFee::class => BookingStatusEnum::REFUND_FEE->value,
            BookingWaitingConfirmation::class => BookingStatusEnum::WAITING_CONFIRMATION->value,
            BookingWaitingCancellation::class => BookingStatusEnum::WAITING_CANCELLATION->value,
            BookingWaitingProcessing::class => BookingStatusEnum::WAITING_PROCESSING->value,
        };
    }
}
