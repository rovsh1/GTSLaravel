<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\Dto\UpdateStatusResponseDto;
use Module\Booking\Moderation\Domain\Booking\Service\StatusUpdater;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWork;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateStatus implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWork $bookingUnitOfWork,
        private readonly StatusUpdater $statusUpdater,
    ) {}

    public function execute(
        int $bookingId,
        int $statusId,
        ?string $notConfirmedReason = null,
        ?float $supplierPenalty = null,
        ?float $clientPenalty = null
    ): UpdateStatusResponseDto {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));
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
        $this->bookingUnitOfWork->commit();

        return new UpdateStatusResponseDto();
    }
}
