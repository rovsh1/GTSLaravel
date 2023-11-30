<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

use Carbon\CarbonImmutable;
use Module\Booking\Shared\Domain\Voucher\Voucher;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class VoucherDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly CarbonImmutable $dateCreate
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof Voucher);

        return new static(
            $entity->id()->value(),
            $entity->dateCreate()
        );
    }
}
