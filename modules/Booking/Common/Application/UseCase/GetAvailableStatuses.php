<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\UseCase;

use Module\Booking\Common\Application\Factory\StatusDtoFactory;
use Module\Booking\Common\Application\Response\StatusDto;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\StatusRules\Rules;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableStatuses implements UseCaseInterface
{
    public function __construct(
        private readonly Rules $statusRulesService,
        private readonly BookingRepositoryInterface $repository,
        private readonly StatusDtoFactory $factory
    ) {}

    /**
     * @param int $bookingId
     * @return StatusDto[]
     */
    public function execute(int $bookingId): array
    {
        $booking = $this->repository->find($bookingId);
        $statuses = $this->statusRulesService->getStatusTransitions($booking->status());
        return array_map(fn(BookingStatusEnum $status) => $this->factory->build($status), $statuses);
    }
}
