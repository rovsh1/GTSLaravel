<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Booking\Hotel\Domain\Entity\Details;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;
use Module\Shared\Infrastructure\Service\JsonSerializer;

class DetailsFactory extends AbstractEntityFactory
{
    public function __construct(
        private readonly JsonSerializer $serializer
    ) {
        parent::__construct();
    }

    /** @var class-string<Details> */
    protected string $entity = Details::class;

    protected function fromArray(array $data): Details
    {
        return new $this->entity(
            $data['hotel_id'],
            $data['date_start'],//date_end
            $this->serializer->deserialize(AdditionalInfo::class, $data['additional_data']),
            $this->serializer->deserialize(Details\RoomCollection::class, $data['rooms']),
        );
    }
}
