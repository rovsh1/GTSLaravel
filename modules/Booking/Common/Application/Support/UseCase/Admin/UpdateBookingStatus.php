<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin;

use Module\Booking\Common\Application\Dto\UpdateStatusResponseDto;
use Module\Booking\Common\Domain\Service\StatusUpdater;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
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
        ?float $cancelFeeAmount = null
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
            case BookingStatusEnum::INVOICED:
                $this->statusUpdater->toInvoiced($booking);
                break;
            case BookingStatusEnum::PAID:
                $this->statusUpdater->toPaid($booking);
                break;
            case BookingStatusEnum::PARTIALLY_PAID:
                $this->statusUpdater->toPartiallyPaid($booking);
                break;
            case BookingStatusEnum::CANCELLED_NO_FEE:
                $this->statusUpdater->toCancelledNoFee($booking);
                break;
            case BookingStatusEnum::CANCELLED_FEE:
                if ($cancelFeeAmount === null) {
                    return new UpdateStatusResponseDto(isCancelFeeAmountRequired: true);
                }
                $this->statusUpdater->toCancelledFee($booking, $cancelFeeAmount);
                break;
            case BookingStatusEnum::REFUND_NO_FEE:
                $this->statusUpdater->toRefundNoFee($booking);
                break;
            case BookingStatusEnum::REFUND_FEE:
                $this->statusUpdater->toRefundFee($booking);
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
