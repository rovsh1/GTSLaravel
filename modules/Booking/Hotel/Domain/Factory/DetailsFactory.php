<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Factory;

use Module\Booking\Hotel\Domain\Entity\Details;
use Module\Shared\Domain\Service\SerializerInterface;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class DetailsFactory extends AbstractEntityFactory
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
        parent::__construct();
    }

    /** @var class-string<Details> */
    protected string $entity = Details::class;

    protected function fromArray(array $data): Details
    {
        return $this->serializer->deserialize($this->entity, $data['data']);
    }
}
