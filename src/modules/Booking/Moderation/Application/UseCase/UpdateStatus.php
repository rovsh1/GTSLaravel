<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\Dto\UpdateStatusResponseDto;
use Module\Booking\Moderation\Domain\Booking\Service\StatusUpdater;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateStatus implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly StatusUpdater $statusUpdater,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function execute(
        int $bookingId,
        int $statusId,
        ?string $notConfirmedReason = null,
        ?float $supplierPenalty = null,
        ?float $clientPenalty = null
    ): UpdateStatusResponseDto {
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));
        $statusEnum = BookingStatusEnum::from($statusId);

        switch ($statusEnum) {
            case BookingStatusEnum::PROCESSING:
                $this->statusUpdater->toProcessing($booking);
                break;
            case BookingStatusEnum::CANCELLED:
                $this->statusUpdater->cancel($booking);
                break;
            case BookingStatusEnum::CONFIRMED:
                $this->statusUpdater->confirm($booking);
                break;
            case BookingStatusEnum::NOT_CONFIRMED:
                $isHotelBooking = true; //@todo заменить на реальную проверку, когда объединим детали с броней
                if (empty($notConfirmedReason) && $isHotelBooking) {
                    return new UpdateStatusResponseDto(isNotConfirmedReasonRequired: true);
                }
                $this->statusUpdater->toNotConfirmed($booking, $notConfirmedReason);
                break;
            case BookingStatusEnum::CANCELLED_NO_FEE:
                $this->statusUpdater->toCancelledNoFee($booking);
                break;
            case BookingStatusEnum::CANCELLED_FEE:
                if ($supplierPenalty === null) {
                    return new UpdateStatusResponseDto(isCancelFeeAmountRequired: true);
                }
                $this->statusUpdater->toCancelledFee($booking, $supplierPenalty, $clientPenalty);
                break;
            case BookingStatusEnum::WAITING_CONFIRMATION:
                $this->statusUpdater->toWaitingConfirmation($booking);
                break;
            case BookingStatusEnum::WAITING_CANCELLATION:
                $this->statusUpdater->toWaitingCancellation($booking);
                break;
            case BookingStatusEnum::WAITING_PROCESSING:
                $this->statusUpdater->toWaitingProcessing($booking);
                break;
        }

        $this->bookingRepository->store($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());

        return new UpdateStatusResponseDto();
    }
}
