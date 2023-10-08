<?php

namespace Module\Catalog\Application\Admin\Response;

use Module\Catalog\Application\Admin\ResponseDto\PriceRateDto;
use Module\Catalog\Domain\Hotel\Entity\Room;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Sdk\Module\Foundation\Support\Dto\DtoCollection;
use Sdk\Module\Foundation\Support\Dto\DtoCollectionOf;

class RoomDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $hotelId,
        public readonly string $name,
        /** @var PriceRateDto[] $priceRates */
        #[DtoCollectionOf(PriceRateDto::class)]
        public readonly DtoCollection $priceRates,
        public readonly int $guestsCount,
        public readonly int $roomsCount,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface $entity): static
    {
        assert($entity instanceof Room);

        return new static(
            $entity->id->value(),
            $entity->hotelId->value(),
            $entity->name,
            PriceRateDto::collection($entity->priceRates),
            $entity->guestsCount,
            $entity->roomsCount,
        );
    }
}
