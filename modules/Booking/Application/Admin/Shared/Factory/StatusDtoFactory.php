<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Factory;

use Module\Booking\Application\Admin\Shared\Response\StatusDto;
use Module\Booking\Domain\Booking\Service\StatusStorageInterface;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;

class StatusDtoFactory
{
    public function __construct(
        private readonly StatusStorageInterface $statusStorage
    ) {}

    public function get(BookingStatusEnum $status): StatusDto
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
        return array_map(fn(BookingStatusEnum $status) => $this->get($status), BookingStatusEnum::cases());
    }
}
