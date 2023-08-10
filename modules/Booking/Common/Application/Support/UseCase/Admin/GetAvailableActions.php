<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin;

use Module\Booking\Common\Application\Response\AvailableActionsDto;
use Module\Booking\Common\Application\Response\StatusDto;
use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\Service\StatusRules\StatusRulesInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly StatusRulesInterface $statusRules,
        private readonly RequestRules $requestRules,
        private readonly BookingRepositoryInterface $repository,
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
            $booking->canSendClientVoucher(),
            $this->statusRules->canEditExternalNumber($booking->status()),
            //@todo прописать логику для этого флага (у отеля и админки она разная)
            $this->statusRules->canChangeRoomPrice($booking->status()) && !$booking->isManualBoPrice(),
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
