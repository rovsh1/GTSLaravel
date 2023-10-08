<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Support\UseCase;

use Module\Booking\Application\Admin\Shared\Response\AvailableActionsDto;
use Module\Booking\Application\Admin\Shared\Response\StatusDto;
use Module\Booking\Application\Admin\Shared\Service\StatusStorage;
use Module\Booking\Domain\Shared\Entity\AbstractBooking;
use Module\Booking\Domain\Shared\Service\RequestRules;
use Module\Booking\Domain\Shared\Service\StatusRules\StatusRulesInterface;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly StatusRulesInterface $statusRules,
        private readonly RequestRules $requestRules,
        private $repository,
        private readonly StatusStorage $statusStorage,
    ) {}

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->find($bookingId);
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
            $this->statusRules->canChangeRoomPrice($booking->status()) && !$booking->price()->clientPrice()->manualValue(),
            $this->statusRules->isCancelledStatus($booking->status()),
        );
    }

    /**
     * @param AbstractBooking $booking
     * @return StatusDto[]
     */
    private function getAvailableStatuses(AbstractBooking $booking): array
    {
        $statuses = $this->statusRules->getStatusTransitions($booking->status());
        $statusIds = array_flip(array_map(fn(BookingStatusEnum $status) => $status->value, $statuses));
        $statusSettings = $this->statusStorage->statuses();

        return array_values(
            array_filter($statusSettings, fn(StatusDto $statusDto) => array_key_exists($statusDto->id, $statusIds))
        );
    }
}
