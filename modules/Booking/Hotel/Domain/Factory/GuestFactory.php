<?php

namespace Module\Booking\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Booking\Hotel\Domain\Entity\Room;
use Module\Booking\Hotel\Domain\ValueObject\GenderEnum;

class GuestFactory extends AbstractEntityFactory
{
    protected string $entity = Room\Guest::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            $data['id'],
            $data['fullname'],
            $data['nationality_id'],
            GenderEnum::from($data['gender'])
        );
    }
}
