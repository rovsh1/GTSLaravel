<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Factory;

use Carbon\Carbon;
use Module\Booking\Common\Domain\Entity\Booking;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class BookingFactory extends AbstractEntityFactory
{
    /** @var class-string<Booking> */
    protected string $entity = Booking::class;

    protected function fromArray(array $data): Booking
    {
        return new $this->entity(
            new Id($data['id']),
            new Id($data['order_id']),
            BookingStatusEnum::from($data['status']),
            BookingTypeEnum::from($data['type']),
            new Carbon($data['created_at']),
            new Id($data['creator_id']),
            $data['note'],
        );
    }
}
