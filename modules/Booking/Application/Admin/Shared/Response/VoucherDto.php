<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Response;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Shared\Entity\Voucher;
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
