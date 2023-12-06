<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity\Details;

use DateTimeInterface;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Enum\QuotaProcessingMethodEnum;
use Sdk\Booking\Event\HotelBooking\BookingPeriodChanged;
use Sdk\Booking\Support\Entity\AbstractDetails;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\HotelBooking\BookingPeriod;
use Sdk\Booking\ValueObject\HotelBooking\ExternalNumber;
use Sdk\Booking\ValueObject\HotelBooking\HotelInfo;
use Sdk\Shared\Enum\ServiceTypeEnum;

final class HotelBooking extends AbstractDetails implements DetailsInterface
{
    public function __construct(
        DetailsId $id,
        BookingId $bookingId,
        private readonly HotelInfo $hotelInfo,
        private BookingPeriod $bookingPeriod,
        private ?ExternalNumber $externalNumber,
        private readonly QuotaProcessingMethodEnum $quotaProcessingMethod,
    ) {
        parent::__construct($id, $bookingId);
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::HOTEL_BOOKING;
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
        if ($this->bookingPeriod->isEqual($period)) {
            return;
        }

        $this->pushEvent(new BookingPeriodChanged($this, $this->bookingPeriod));
        $this->bookingPeriod = $period;
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
