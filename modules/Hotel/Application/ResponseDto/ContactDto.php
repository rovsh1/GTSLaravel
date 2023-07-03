<?php

declare(strict_types=1);

namespace Module\Hotel\Application\ResponseDto;

use Module\Hotel\Domain\ValueObject\Contact;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class ContactDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $type,
        public readonly string $value,
        public readonly bool $isMain
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Contact $entity): static
    {
        return new static(
            $entity->type()->value,
            $entity->value(),
            $entity->isMain(),
        );
    }
}
