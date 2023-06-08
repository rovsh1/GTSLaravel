<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin;

use Module\Booking\Common\Application\Dto\AvailableActionsDto;
use Module\Booking\Common\Application\Factory\StatusDtoFactory;
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
        private readonly StatusDtoFactory $factory
    ) {}

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->find($bookingId);
        $statuses = $this->statusRules->getStatusTransitions($booking->status());
        $statusesDto = array_map(fn(BookingStatusEnum $status) => $this->factory->build($status), $statuses);

        return new AvailableActionsDto(
            $statusesDto,
            $this->statusRules->isEditableStatus($booking->status(), $this->requestRules),
            $this->requestRules->isRequestableStatus($booking->status()),
            $this->requestRules->canSendBookingRequest($booking->status()),
            $this->requestRules->canSendCancellationRequest($booking->status()),
            $this->requestRules->canSendChangeRequest($booking->status()),
            $booking->canSendClientVoucher(),
            true, //@todo прописать логику для этого флага (у отеля и админки она разная)
        );
    }
}
