<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Hotel\Domain\Entity\Booking;

class BookingFactory extends AbstractEntityFactory
{
    /** @var class-string<Booking> */
    protected string $entity = Booking::class;

    protected function fromArray(array $data): Booking
    {
        return new $this->entity(
            $data['id'],
            BookingStatusEnum::from($data['status']),
            BookingTypeEnum::from($data['type']),
            $data['note'],
        );
    }
}
