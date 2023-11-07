<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Order\Entity\Voucher;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class VoucherDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly CarbonImmutable $dateCreate
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Voucher $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->dateCreate()
        );
    }
}
