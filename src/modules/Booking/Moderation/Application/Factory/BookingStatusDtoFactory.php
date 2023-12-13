<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Module\Booking\Moderation\Application\Dto\BookingStatusDto;
use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Sdk\Booking\ValueObject\BookingStatus;

class BookingStatusDtoFactory
{
    public function __construct(
        private readonly BookingStatusStorageInterface $statusStorage
    ) {}

    public function build(BookingStatus $status): BookingStatusDto
    {
        return new BookingStatusDto(
            $status->value()->value,
            $this->statusStorage->getName($status->value()),
            $this->statusStorage->getColor($status->value()),
            $status->reason(),
        );
    }
}
