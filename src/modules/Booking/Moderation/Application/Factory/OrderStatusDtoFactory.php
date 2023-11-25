<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Module\Booking\Shared\Domain\Order\Service\OrderStatusStorageInterface;
use Sdk\Booking\Dto\StatusDto;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

class OrderStatusDtoFactory
{
    public function __construct(
        private readonly OrderStatusStorageInterface $statusStorage
    ) {}

    public function get(OrderStatusEnum $status): StatusDto
    {
        return new StatusDto(
            $status->value,
            $this->statusStorage->getName($status),
            $this->statusStorage->getColor($status),
        );
    }

    /**
     * @return StatusDto[]
     */
    public function statuses(): array
    {
        return array_map(fn(OrderStatusEnum $status) => $this->get($status), OrderStatusEnum::cases());
    }
}
