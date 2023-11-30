<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Dto;

use Module\Hotel\Moderation\Domain\Hotel\ValueObject\Contact;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class ContactDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $type,
        public readonly string $value,
        public readonly bool $isMain
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof Contact);

        return new static(
            $entity->type()->value,
            $entity->value(),
            $entity->isMain(),
        );
    }
}
