<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherGenerator\Dto;

use Illuminate\Support\Collection;
use Module\Booking\Moderation\Domain\Order\Service\VoucherGenerator\Dto\Service\CancelConditionsDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherGenerator\Dto\Service\GuestDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherGenerator\Dto\Service\PriceDto;

class ServiceInfoDto
{
    public function __construct(
        public readonly string $title,
        public readonly Collection $detailOptions,
        /** @var GuestDto[] $guests */
        public readonly array $guests,
        public readonly PriceDto $price,
        public readonly CancelConditionsDto $cancelConditions,
        public readonly string $status,
        public readonly ?string $externalNumber = null,
    ) {}
}
