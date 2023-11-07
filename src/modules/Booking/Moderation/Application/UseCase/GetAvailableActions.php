<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\Dto\AvailableActionsDto;
use Module\Booking\Moderation\Application\Dto\StatusDto;
use Module\Booking\Moderation\Application\Factory\BookingStatusDtoFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Shared\Service\RequestRules;
use Module\Booking\Shared\Domain\Shared\Service\StatusRules\AdministratorRules;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly AdministratorRules $statusRules,
        private readonly RequestRules $requestRules,
        private BookingRepositoryInterface $repository,
        private readonly BookingStatusDtoFactory $statusDtoFactory,
    ) {}

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));
        $statuses = $this->getAvailableStatuses($booking);

        return new AvailableActionsDto(
            $statuses,
            $this->statusRules->isEditableStatus($booking->status()),
            $this->requestRules->isRequestableStatus($booking->status()),
            $this->requestRules->canSendBookingRequest($booking->status()),
            $this->requestRules->canSendCancellationRequest($booking->status()),
            $this->requestRules->canSendChangeRequest($booking->status()),
            $this->statusRules->canEditExternalNumber($booking->status()),
            //@todo прописать логику для этого флага (у отеля и админки она разная)
            $this->statusRules->canChangeRoomPrice($booking->status()) && !$booking->prices()->clientPrice(
            )->manualValue(),
            $this->statusRules->isCancelledStatus($booking->status()),
        );
    }

    /**
     * @param Booking $booking
     * @return StatusDto[]
     */
    private function getAvailableStatuses(Booking $booking): array
    {
        $statuses = $this->statusRules->getStatusTransitions($booking->status());
        $statusIds = array_flip(array_map(fn(BookingStatusEnum $status) => $status->value, $statuses));
        $statusSettings = $this->statusDtoFactory->statuses();

        return array_values(
            array_filter($statusSettings, fn(StatusDto $statusDto) => array_key_exists($statusDto->id, $statusIds))
        );
    }
}
