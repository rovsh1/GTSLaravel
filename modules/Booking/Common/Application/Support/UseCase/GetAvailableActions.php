<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase;

use Module\Booking\Common\Application\Dto\AvailableActionsDto;
use Module\Booking\Common\Application\Factory\StatusDtoFactory;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\StatusRules\RequestRules;
use Module\Booking\Common\Domain\Service\StatusRules\Rules;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly Rules $statusRules,
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
            $this->requestRules->isRequestableStatus($booking->status())
        );
    }
}
