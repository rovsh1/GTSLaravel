<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Factory;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\BookingRequest\BookingRequest;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestId;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Shared\ValueObject\File;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class RequestFactory extends AbstractEntityFactory
{
    protected string $entity = BookingRequest::class;

    protected function fromArray(array $data): BookingRequest
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
