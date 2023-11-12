<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\Factory\OrderStatusDtoFactory;
use Module\Booking\Shared\Application\Dto\StatusDto;
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
