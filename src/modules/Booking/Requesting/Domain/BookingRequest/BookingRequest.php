<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\BookingRequest;

use Carbon\CarbonImmutable;
use Module\Booking\Requesting\Domain\BookingRequest\ValueObject\RequestId;
use Module\Booking\Requesting\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Shared\Contracts\Domain\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\ValueObject\File;

class BookingRequest extends AbstractAggregateRoot implements EntityInterface
{
    //@todo нужен статус вместо флага + кнопка сформировать запрос, после этого появляются 2 кнопки: отправить в отель и переформировать
    public function __construct(
        private readonly RequestId $id,
        private readonly BookingId $bookingId,
        private readonly RequestTypeEnum $type,
        private readonly File $file,
        private readonly CarbonImmutable $dateCreate,
    ) {
    }

    public function id(): RequestId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function type(): RequestTypeEnum
    {
        return $this->type;
    }

    public function file(): File
    {
        return $this->file;
    }

    public function dateCreate(): CarbonImmutable
    {
        return $this->dateCreate;
    }
}
