<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Factory;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Shared\Entity\Request;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\RequestId;
use Module\Booking\Domain\Shared\ValueObject\RequestTypeEnum;
use Module\Shared\ValueObject\File;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class RequestFactory extends AbstractEntityFactory
{
    protected string $entity = Request::class;

    protected function fromArray(array $data): Request
    {
        return new $this->entity(
            new RequestId($data['id']),
            new BookingId($data['booking_id']),
            RequestTypeEnum::from($data['type']),
            new File($data['file']),
            new CarbonImmutable($data['created_at']),
        );
    }
}