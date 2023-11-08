<?php

namespace Module\Booking\Requesting\Domain\BookingRequest\Service\TemplateData\AirportBooking;

use DateTimeInterface;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\AirportBooking\AirportDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\AirportBooking\ContractDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\GuestDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\Dto\ServiceDto;
use Module\Booking\Requesting\Domain\BookingRequest\Service\TemplateDataInterface;

final class BookingRequest implements TemplateDataInterface
{
    public function __construct(
        private readonly AirportDto $airport,
        private readonly ContractDto $contract,
        private readonly ServiceDto $service,
        /** @var GuestDto[] */
        private readonly array $guests,
        private readonly ?string $flightNumber,
        private readonly ?DateTimeInterface $serviceDate,
    ) {}

    public function toArray(): array
    {
        return [
            'contract' => $this->contract,
            'airport' => $this->airport,
            'service' => $this->service,
            'guests' => $this->guests,
            'flightNumber' => $this->flightNumber,
            'date' => $this->serviceDate?->format('d.m.Y'),
            'time' => $this->serviceDate?->format('H:i'),
        ];
    }
}
