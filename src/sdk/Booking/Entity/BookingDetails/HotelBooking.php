<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\BookingDetails;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\HotelBooking\BookingPeriod;
use Sdk\Booking\ValueObject\HotelBooking\ExternalNumber;
use Sdk\Booking\ValueObject\HotelBooking\HotelInfo;
use Sdk\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;

final class HotelBooking implements DetailsInterface
{
    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly HotelInfo $hotelInfo,
        private BookingPeriod $bookingPeriod,
        private ?ExternalNumber $externalNumber,
        private readonly QuotaProcessingMethodEnum $quotaProcessingMethod,
    ) {
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::HOTEL_BOOKING;
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function hotelInfo(): HotelInfo
    {
        return $this->hotelInfo;
    }

    public function bookingPeriod(): BookingPeriod
    {
        return $this->bookingPeriod;
    }

    public function setBookingPeriod(BookingPeriod $period): void
    {
        if (!$this->bookingPeriod->isEqual($period)) {
            $this->bookingPeriod = $period;
        }
    }

    public function externalNumber(): ?ExternalNumber
    {
        return $this->externalNumber;
    }

    public function setExternalNumber(ExternalNumber|null $number): void
    {
        $this->externalNumber = $number;
    }

    public function quotaProcessingMethod(): QuotaProcessingMethodEnum
    {
        return $this->quotaProcessingMethod;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'hotelInfo' => $this->hotelInfo->serialize(),
            'bookingPeriod' => $this->bookingPeriod->serialize(),
            'externalNumber' => $this->externalNumber?->serialize(),
            'quotaProcessingMethod' => $this->quotaProcessingMethod->value,
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new HotelBooking(
            new DetailsId($payload['id']),
            new BookingId($payload['bookingId']),
            HotelInfo::deserialize($payload['hotelInfo']),
            BookingPeriod::deserialize($payload['bookingPeriod']),
            $payload['externalNumber'] ? ExternalNumber::deserialize($payload['externalNumber']) : null,
            QuotaProcessingMethodEnum::from($payload['quotaProcessingMethod'])
        );
    }

    public function serviceDate(): ?DateTimeInterface
    {
        return $this->bookingPeriod->dateFrom();
    }
}
