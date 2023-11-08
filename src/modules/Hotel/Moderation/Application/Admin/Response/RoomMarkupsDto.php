<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\Response;

use Module\Hotel\Moderation\Domain\Hotel\Entity\Room\RoomMarkups;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class RoomMarkupsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $discount,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|RoomMarkups $entity): static
    {
        return new static(
            $entity->discount()->value(),
        );
    }
}
