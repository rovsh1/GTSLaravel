<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Dto;

use Carbon\CarbonImmutable;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\AdditionalInfoDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\BookingPeriodDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\CancelConditionsDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\HotelInfoDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBookingDto;
use Module\Booking\Application\Admin\Shared\Response\BookingDto as BaseDto;
use Module\Booking\Application\Admin\Shared\Response\BookingPriceDto;
use Module\Booking\Application\Admin\Shared\Response\StatusDto;
use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

class BookingDto extends BaseDto
{
    public function __construct(
        int $id,
        StatusDto $status,
        int $orderId,
        CarbonImmutable $createdAt,
        int $creatorId,
        BookingPriceDto $prices,
        CancelConditionsDto $cancelConditions,
        ?string $note,
        public readonly HotelInfoDto $hotelInfo,
        public readonly BookingPeriodDto $period,
        public readonly ?AdditionalInfoDto $additionalInfo,
        /** @var RoomBookingDto[] $rooms */
        public readonly array $roomBookings,
        public readonly QuotaProcessingMethodEnum $quotaProcessingMethod,
    ) {
        parent::__construct($id, $status, $orderId, $createdAt, $creatorId, $prices, $cancelConditions, $note);
    }

    public static function fromDomain(EntityInterface|BookingInterface|ValueObjectInterface|HotelBooking $entity
    ): static {
        return new static(
            $entity->id()->value(),
            $entity->status()->value,
            $entity->orderId()->value(),
            $entity->createdAt(),
            $entity->creatorId()->value(),
            BookingPriceDto::fromDomain($entity->price()),
            CancelConditionsDto::fromDomain($entity->cancelConditions()),
            $entity->note(),
            HotelInfoDto::fromDomain($entity->hotelInfo()),
            BookingPeriodDto::fromDomain($entity->period()),
            $entity->additionalInfo() !== null ? AdditionalInfoDto::fromDomain($entity->additionalInfo()) : null,
            RoomBookingDto::collectionFromDomain($entity->roomBookings()->all()),
            $entity->quotaProcessingMethod()
        );
    }
}
