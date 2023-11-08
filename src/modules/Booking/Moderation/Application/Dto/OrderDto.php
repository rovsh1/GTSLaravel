<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

use Carbon\CarbonImmutable;
use Module\Shared\Dto\CurrencyDto;
use Module\Shared\Enum\SourceEnum;
use Sdk\Module\Foundation\Support\Dto\Dto;

class OrderDto extends Dto
{
    public function __construct(
        public readonly int $id,
        public readonly CurrencyDto $currency,
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly CarbonImmutable $createdAt,
        /** @var int[] $guestIds */
        public readonly array $guestIds,
        public readonly int $creatorId,
        public readonly SourceEnum $source,
    ) {}
}
