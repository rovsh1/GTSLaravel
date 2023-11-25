<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Application\Factory;

use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Sdk\Booking\Dto\StatusDto;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;

class BookingStatusDtoFactory
{
    public function __construct(
        private readonly BookingStatusStorageInterface $statusStorage
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
