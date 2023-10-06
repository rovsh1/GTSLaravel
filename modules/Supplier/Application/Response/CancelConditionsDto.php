<?php

declare(strict_types=1);

namespace Module\Supplier\Application\Response;

use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
use Module\Supplier\Domain\Supplier\ValueObject\CancelConditions;

class CancelConditionsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly CancelMarkupOptionDto $noCheckInMarkup,
        /** @var DailyMarkupDto[] $dailyMarkups */
        public readonly array $dailyMarkups
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface $entity): static
    {
        assert($entity instanceof CancelConditions);

        return new static(
            CancelMarkupOptionDto::fromDomain($entity->noCheckInMarkup()),
            DailyMarkupDto::collectionFromDomain($entity->dailyMarkups()->all())
        );
    }
}
