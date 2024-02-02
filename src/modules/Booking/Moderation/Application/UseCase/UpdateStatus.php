<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\Dto\UpdateStatusResponseDto;
use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\StatusTransitionsFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Module\Booking\Shared\Domain\Shared\Exception\InvalidStatusTransition;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;

class UpdateStatus implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly StatusTransitionsFactory $statusTransitionsFactory,
    ) {}

    public function execute(
        int $bookingId,
        int $statusId,
        ?string $notConfirmedReason = null,
        ?float $supplierPenalty = null,
        ?float $clientPenalty = null
    ): UpdateStatusResponseDto {
        $statusEnum = StatusEnum::from($statusId);
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));

        $this->ensureCanTransitToStatus($booking, $statusEnum);

        $isHotelBooking = $booking->serviceType() === ServiceTypeEnum::HOTEL_BOOKING;
        switch ($statusEnum) {
            case StatusEnum::PROCESSING:
                $booking->toProcessing();
                break;
            case StatusEnum::CANCELLED:
                $booking->cancel();
                break;
            case StatusEnum::CONFIRMED:
                $booking->confirm();
                break;
            case StatusEnum::NOT_CONFIRMED:
                if ($isHotelBooking && empty($notConfirmedReason)) {
                    return new UpdateStatusResponseDto(isNotConfirmedReasonRequired: true);
                }
                $booking->toNotConfirmed($notConfirmedReason);
                break;
            case StatusEnum::CANCELLED_NO_FEE:
                $booking->toCancelledNoFee();
                break;
            case StatusEnum::CANCELLED_FEE:
                if (empty($supplierPenalty)) {
                    return new UpdateStatusResponseDto(isCancelFeeAmountRequired: true);
                }
                $booking->toCancelledFee($supplierPenalty, $clientPenalty);
                break;
            case StatusEnum::WAITING_CONFIRMATION:
                $booking->toWaitingConfirmation();
                break;
            case StatusEnum::WAITING_CANCELLATION:
                $booking->toWaitingCancellation();
                break;
            case StatusEnum::WAITING_PROCESSING:
                $booking->toWaitingProcessing();
                break;
            default:
                throw new \LogicException('Status change not implemented');
        }

        $this->bookingUnitOfWork->commit();

        return new UpdateStatusResponseDto();
    }

    private function ensureCanTransitToStatus(Booking $booking, StatusEnum $statusTo): void
    {
        $statusTransitions = $this->statusTransitionsFactory->build($booking->serviceType());

        if (!$statusTransitions->canTransit($booking->status()->value(), $statusTo)) {
            throw new InvalidStatusTransition("Can't change status for booking [{$booking->id()->value()}]]");
        }
    }
}
