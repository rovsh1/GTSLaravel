<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Response;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Shared\Entity\Voucher;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

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
