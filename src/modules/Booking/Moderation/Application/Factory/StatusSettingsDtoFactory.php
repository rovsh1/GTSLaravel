<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Sdk\Booking\Dto\StatusDto;
use Sdk\Booking\Enum\StatusEnum;

class StatusSettingsDtoFactory
{
    public function __construct(
        private readonly BookingStatusStorageInterface $statusStorage
    ) {}

    public function get(StatusEnum $status): StatusDto
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
        return array_map(fn(StatusEnum $status) => $this->get($status), StatusEnum::cases());
    }
}
