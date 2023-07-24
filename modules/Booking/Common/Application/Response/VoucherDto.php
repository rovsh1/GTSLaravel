<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Response;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\Voucher;
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
