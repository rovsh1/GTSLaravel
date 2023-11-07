<?php

declare(strict_types=1);

namespace Module\Booking\Application\UseCase\Admin;

use Module\Booking\Application\Dto\StatusDto;
use Module\Booking\Application\Factory\BookingStatusDtoFactory;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatuses implements UseCaseInterface
{
    public function __construct(private readonly BookingStatusDtoFactory $statusDtoFactory) {}

    /**
     * @return StatusDto[]
     */
    public function execute(): array
    {
        return $this->statusDtoFactory->statuses();
    }
}
