<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

use Carbon\CarbonImmutable;
use Module\Booking\Shared\Application\Dto\StatusDto;
use Module\Shared\Dto\MoneyDto;
use Module\Shared\Enum\SourceEnum;

class OrderDto
{
    public function __construct(
        public readonly int $id,
        public readonly StatusDto $status,
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly CarbonImmutable $createdAt,
        /** @var int[] $guestIds */
        public readonly array $guestIds,
        public readonly int $creatorId,
        public readonly MoneyDto $clientPrice,
        public readonly SourceEnum $source,
    ) {}
}
