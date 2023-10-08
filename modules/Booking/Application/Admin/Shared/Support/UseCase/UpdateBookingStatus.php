<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Support\UseCase;

use Module\Booking\Application\Admin\Shared\Response\UpdateStatusResponseDto;
use Module\Booking\Domain\Shared\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\Service\StatusUpdater;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateBookingStatus implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly StatusUpdater $statusUpdater
    ) {}

    public function execute(
        int $bookingId,
        int $statusId,
        ?string $notConfirmedReason = null,
        ?float $netPenalty = null,
        ?float $grossPenalty = null
    ): UpdateStatusResponseDto {
        $booking = $this->repository->find($bookingId);
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
                if ($notConfirmedReason === null) {
                    return new UpdateStatusResponseDto(isNotConfirmedReasonRequired: true);
                }
                $this->statusUpdater->toNotConfirmed($booking, $notConfirmedReason);
                break;
            case BookingStatusEnum::CANCELLED_NO_FEE:
                $this->statusUpdater->toCancelledNoFee($booking);
                break;
            case BookingStatusEnum::CANCELLED_FEE:
                if ($netPenalty === null) {
                    return new UpdateStatusResponseDto(isCancelFeeAmountRequired: true);
                }
                $this->statusUpdater->toCancelledFee($booking, $netPenalty, $grossPenalty);
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

        return new UpdateStatusResponseDto();
    }
}
