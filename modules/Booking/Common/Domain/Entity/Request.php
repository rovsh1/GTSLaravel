<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\RequestId;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Shared\Domain\Entity\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Request extends AbstractAggregateRoot implements EntityInterface
{
    //@todo нужен статус вместо флага + кнопка сформировать запрос, после этого появляются 2 кнопки: отправить в отель и переформировать
    public function __construct(
        private readonly RequestId $id,
        private readonly BookingId $bookingId,
        private readonly RequestTypeEnum $type,
        private readonly CarbonImmutable $dateCreate,
    ) {}

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

    public function dateCreate(): CarbonImmutable
    {
        return $this->dateCreate;
    }

    public function getFilename(): string
    {
        return match ($this->type) {
            RequestTypeEnum::BOOKING => "new_booking_{$this->bookingId->value()}",
            RequestTypeEnum::CHANGE => "change_booking_{$this->bookingId->value()}",
            RequestTypeEnum::CANCEL => "cancel_booking_{$this->bookingId->value()}",
        };
    }
}
