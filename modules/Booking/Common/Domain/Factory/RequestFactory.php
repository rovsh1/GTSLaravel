<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Factory;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\RequestId;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
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
