<?php

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData\AirportBooking;

use DateTimeInterface;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\AirportBooking\AirportDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\AirportBooking\ContractDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\GuestDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\ServiceDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData\TemplateDataInterface;

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
