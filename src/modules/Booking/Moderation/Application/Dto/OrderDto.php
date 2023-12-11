<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriodImmutable;
use Sdk\Booking\Dto\StatusDto;
use Sdk\Shared\Dto\MoneyDto;
use Sdk\Shared\Enum\SourceEnum;

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
        public readonly ?VoucherDto $voucher,
        public readonly ?CarbonPeriodImmutable $period,
    ) {}
}
