<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\Response;

use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelMarkupOption;

class CancelMarkupOptionDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $percent,
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof CancelMarkupOption);

        return new static(
            $entity->percent()->value(),
        );
    }
}
