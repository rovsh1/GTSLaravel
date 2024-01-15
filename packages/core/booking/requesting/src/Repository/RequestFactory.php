<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Repository;

use Carbon\CarbonImmutable;
use Pkg\Booking\Requesting\Domain\Entity\BookingRequest;
use Pkg\Booking\Requesting\Domain\ValueObject\RequestId;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Sdk\Shared\ValueObject\File;

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
