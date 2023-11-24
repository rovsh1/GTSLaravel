<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\Dto\UpdateStatusResponseDto;
use Module\Booking\Moderation\Domain\Booking\Exception\InvalidStatusTransition;
use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\StatusTransitionsFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateStatus implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly StatusTransitionsFactory $statusTransitionsFactory,
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
        $statusEnum = BookingStatusEnum::from($statusId);
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));

        $this->ensureCanTransitToStatus($booking, $statusEnum);

        $isHotelBooking = $booking->serviceType() === ServiceTypeEnum::HOTEL_BOOKING;
        switch ($statusEnum) {
            case BookingStatusEnum::PROCESSING:
                $booking->toProcessing();
                break;
            case BookingStatusEnum::CANCELLED:
                $booking->cancel();
                break;
            case BookingStatusEnum::CONFIRMED:
                $booking->confirm();
                break;
            case BookingStatusEnum::NOT_CONFIRMED:
                if ($isHotelBooking && empty($notConfirmedReason)) {
                    return new UpdateStatusResponseDto(isNotConfirmedReasonRequired: true);
                }
                $booking->toNotConfirmed($notConfirmedReason);
                break;
            case BookingStatusEnum::CANCELLED_NO_FEE:
                $booking->toCancelledNoFee();
                break;
            case BookingStatusEnum::CANCELLED_FEE:
                if ($isHotelBooking && empty($supplierPenalty)) {
                    return new UpdateStatusResponseDto(isCancelFeeAmountRequired: true);
                }
                $booking->toCancelledFee($supplierPenalty, $clientPenalty);
                break;
            case BookingStatusEnum::WAITING_CONFIRMATION:
                $booking->toWaitingConfirmation();
                break;
            case BookingStatusEnum::WAITING_CANCELLATION:
                $booking->toWaitingCancellation();
                break;
            case BookingStatusEnum::WAITING_PROCESSING:
                $booking->toWaitingProcessing();
                break;
            default:
                throw new \LogicException('Status change not implemented');
        }

        $this->bookingRepository->store($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());

        return new UpdateStatusResponseDto();
    }

    private function ensureCanTransitToStatus(Booking $booking, BookingStatusEnum $statusTo): void
    {
        $statusTransitions = $this->statusTransitionsFactory->build($booking->serviceType());

        if (!$statusTransitions->canTransit($booking->status(), $statusTo)) {
            throw new InvalidStatusTransition("Can't change status for booking [{$booking->id()->value()}]]");
        }
    }
}
