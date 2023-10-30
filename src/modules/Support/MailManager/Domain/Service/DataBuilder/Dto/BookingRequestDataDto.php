<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Domain\Service\DataBuilder\Dto;

final class BookingRequestDataDto implements BookingDataDtoInterface
{
    public function __construct(
        public readonly int $bookingId,
        public readonly int $requestId,
    ) {
    }
}