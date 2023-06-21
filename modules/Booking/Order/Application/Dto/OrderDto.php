<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Dto;

use Module\Booking\Order\Domain\Entity\Order;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class OrderDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $clientId,
        public readonly ?int $legalId,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Order $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->clientId()->value(),
            $entity->legalId()?->value()
        );
    }
}
