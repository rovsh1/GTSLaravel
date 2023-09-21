<?php

namespace Module\Hotel\Application\Response;

use Module\Hotel\Application\ResponseDto\PriceRateDto;
use Module\Hotel\Domain\Entity\Room;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
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
