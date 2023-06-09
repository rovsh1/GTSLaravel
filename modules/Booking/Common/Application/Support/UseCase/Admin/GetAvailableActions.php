<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin;

use Module\Booking\Common\Application\Dto\AvailableActionsDto;
use Module\Booking\Common\Application\Dto\StatusDto;
use Module\Booking\Common\Application\Query\Admin\GetStatusSettings;
use Module\Booking\Common\Domain\Entity\Booking;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\Service\StatusRules\StatusRulesInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly StatusRulesInterface $statusRules,
        private readonly RequestRules $requestRules,
        private readonly BookingRepositoryInterface $repository,
        private readonly QueryBusInterface $queryBus
    ) {}

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->find($bookingId);
        $statuses = $this->getAvailableStatuses($booking);

        return new AvailableActionsDto(
            $statuses,
            $this->statusRules->isEditableStatus($booking->status(), $this->requestRules),
            $this->requestRules->isRequestableStatus($booking->status()),
            $this->requestRules->canSendBookingRequest($booking->status()),
            $this->requestRules->canSendCancellationRequest($booking->status()),
            $this->requestRules->canSendChangeRequest($booking->status()),
            $booking->canSendClientVoucher(),
            true, //@todo прописать логику для этого флага (у отеля и админки она разная)
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
        $statusSettings = $this->queryBus->execute(new GetStatusSettings());

        return array_values(
            array_filter($statusSettings, fn(StatusDto $statusDto) => array_key_exists($statusDto->id, $statusIds))
        );
    }
}
