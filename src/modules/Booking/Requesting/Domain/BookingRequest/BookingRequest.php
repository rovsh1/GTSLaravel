<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\BookingRequest;

use Carbon\CarbonImmutable;
use Module\Booking\Requesting\Domain\BookingRequest\ValueObject\RequestId;
use Module\Booking\Requesting\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Shared\Contracts\Domain\EntityInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\ValueObject\File;

final class BookingRequest extends AbstractAggregateRoot implements EntityInterface, SerializableInterface
{
    //@todo нужен статус вместо флага + кнопка сформировать запрос, после этого появляются 2 кнопки: отправить в отель и переформировать
    public function __construct(
        private readonly RequestId $id,
        private readonly BookingId $bookingId,
        private readonly RequestTypeEnum $type,
        private readonly File $file,
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

    public function file(): File
    {
        return $this->file;
    }

    public function dateCreate(): CarbonImmutable
    {
        return $this->dateCreate;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'type' => $this->type->value,
            'file' => $this->file->guid(),
            'createdAt' => $this->dateCreate->getTimestamp()
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new BookingRequest(
            new RequestId($payload['id']),
            new BookingId($payload['bookingId']),
            RequestTypeEnum::from($payload['type']),
            new File($payload['guid']),
            CarbonImmutable::createFromTimestamp($payload['createdAt'])
        );
    }
}
