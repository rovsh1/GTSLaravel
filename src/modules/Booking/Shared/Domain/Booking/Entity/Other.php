<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Entity;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Shared\Enum\ServiceTypeEnum;

final class Other implements DetailsInterface
{
    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly ServiceInfo $serviceInfo,
        private ?string $description,
    ) {
    }

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
        return null;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'serviceInfo' => $this->serviceInfo->toData(),
            'description' => $this->description,
        ];
    }

    public static function fromData(array $data): static
    {
        return new Other(
            new DetailsId($data['id']),
            new BookingId($data['bookingId']),
            ServiceInfo::fromData($data['serviceInfo']),
            $data['description'],
        );
    }
}
