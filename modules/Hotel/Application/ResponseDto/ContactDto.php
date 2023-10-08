<?php

declare(strict_types=1);

namespace Module\Hotel\Application\ResponseDto;

use Module\Hotel\Domain\ValueObject\Contact;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

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
