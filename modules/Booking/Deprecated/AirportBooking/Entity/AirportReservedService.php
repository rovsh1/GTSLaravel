<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\AirportBooking\Entity;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Module\Booking\Deprecated\AirportBooking\ValueObject\Details\AdditionalInfo;
use Module\Booking\Deprecated\AirportBooking\ValueObject\Details\AirportInfo;
use Module\Booking\Deprecated\AirportBooking\ValueObject\Details\ServiceInfo;
use Module\Booking\Deprecated\ReservedServiceInterface;
use Module\Booking\Deprecated\ServiceInfoInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;

class AirportReservedService implements ReservedServiceInterface
{
    public function __construct(
        private readonly int $id,
        private readonly ServiceInfo $serviceInfo,
        private readonly AirportInfo $airportInfo,
        private readonly AdditionalInfo $additionalInfo,
        private readonly GuestIdCollection $guestIds,
        private readonly CarbonImmutable $date,
        private ?string $note
    ) {}

    public function id(): mixed
    {
        return $this->id;
    }

    public function type()
    {
        return 'airport';
    }

    public function serviceInfo(): ServiceInfoInterface
    {
        return $this->serviceInfo;
    }

    public function airportInfo(): AirportInfo
    {
        return $this->airportInfo;
    }

    public function additionalInfo(): AdditionalInfo
    {
        return $this->additionalInfo;
    }

    public function guestIds(): GuestIdCollection
    {
        return $this->guestIds;
    }

    public function dateStart(): CarbonInterface
    {
        return $this->date;
    }

    public function dateEnd(): ?CarbonInterface
    {
        return null;
    }

    public function note(): ?string
    {
        return $this->note;
    }

    public function price(): BookingPrice
    {
        // TODO: Implement price() method.
    }

    public function conditions()
    {
        // TODO: Implement conditions() method.
    }
}
