<?php

declare(strict_types=1);

namespace Module\Booking\Application\UseCase\Admin\Order;

use Module\Booking\Application\Dto\StatusDto;
use Module\Booking\Application\Factory\OrderStatusDtoFactory;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatuses implements UseCaseInterface
{
    public function __construct(private readonly OrderStatusDtoFactory $statusDtoFactory) {}

    /**
     * @return StatusDto[]
     */
    public function execute(): array
    {
        return $this->statusDtoFactory->statuses();
    }
}
