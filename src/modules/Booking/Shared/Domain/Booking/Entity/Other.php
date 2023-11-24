<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Support\DateTimeImmutable;

final class Other implements DetailsInterface
{
    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private ?string $description,
        private ?DateTimeInterface $date,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::OTHER_SERVICE;
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function serviceDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'serviceInfo' => $this->serviceInfo->serialize(),
            'description' => $this->description,
            'date' => $this->date?->getTimestamp(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        $date = $payload['date'] ?? null;

        return new Other(
            new DetailsId($payload['id']),
            new BookingId($payload['bookingId']),
            ServiceInfo::deserialize($payload['serviceInfo']),
            $payload['description'],
            $date !== null ? DateTimeImmutable::createFromTimestamp($date) : null
        );
    }
}
