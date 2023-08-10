<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Dto;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Application\Response\BookingDto as BaseDto;
use Module\Booking\Common\Application\Response\StatusDto;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\HotelBooking\Application\Dto\Details\AdditionalInfoDto;
use Module\Booking\HotelBooking\Application\Dto\Details\BookingPeriodDto;
use Module\Booking\HotelBooking\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\HotelBooking\Application\Dto\Details\HotelInfoDto;
use Module\Booking\HotelBooking\Application\Dto\Details\RoomBookingDto;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

class BookingDto extends BaseDto
{
    public function __construct(
        int $id,
        StatusDto $status,
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
        public readonly QuotaProcessingMethodEnum $quotaProcessingMethod,
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
            BookingPriceDto::fromDomain($entity->price()),
            $entity->quotaProcessingMethod()
        );
    }
}
