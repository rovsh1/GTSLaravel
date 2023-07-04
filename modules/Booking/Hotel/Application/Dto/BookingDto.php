<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Application\Response\BookingDto as BaseDto;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Hotel\Application\Dto\Details\AdditionalInfoDto;
use Module\Booking\Hotel\Application\Dto\Details\BookingPeriodDto;
use Module\Booking\Hotel\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\Hotel\Application\Dto\Details\HotelInfoDto;
use Module\Booking\Hotel\Application\Dto\Details\RoomBookingDto;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class BookingDto extends BaseDto
{
    public function __construct(
        int $id,
        int $status,
        int $orderId,
        CarbonImmutable $createdAt,
        int $creatorId,
        public readonly ?string $note,
        public readonly HotelInfoDto $hotelInfo,
        public readonly BookingPeriodDto $period,
        public readonly ?AdditionalInfoDto $additionalInfo,
        /** @var RoomBookingDto[] $rooms */
        public readonly array $roomBookings,
        public readonly CancelConditionsDto $cancelConditions,
        public readonly BookingPriceDto $price,
    ) {
        parent::__construct($id, $status, $orderId, $createdAt, $creatorId);
    }

    public static function fromDomain(EntityInterface|BookingInterface|ValueObjectInterface|Booking $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->status()->value,
            $entity->orderId()->value(),
            $entity->createdAt(),
            $entity->creatorId()->value(),
            $entity->note(),
            HotelInfoDto::fromDomain($entity->hotelInfo()),
            BookingPeriodDto::fromDomain($entity->period()),
            $entity->additionalInfo() !== null ? AdditionalInfoDto::fromDomain($entity->additionalInfo()) : null,
            RoomBookingDto::collectionFromDomain($entity->roomBookings()->all()),
            CancelConditionsDto::fromDomain($entity->cancelConditions()),
            BookingPriceDto::fromDomain($entity->price())
        );
    }
}
