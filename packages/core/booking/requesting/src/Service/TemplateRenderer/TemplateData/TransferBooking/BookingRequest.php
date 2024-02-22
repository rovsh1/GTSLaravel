<?php

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData\TransferBooking;

use Illuminate\Support\Collection;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\ServiceDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\TransferBooking\BookingPeriodDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\TransferBooking\CarDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateData\TemplateDataInterface;
use Sdk\Module\Support\DateTimeImmutable;

final class BookingRequest implements TemplateDataInterface
{
    public function __construct(
        private readonly ServiceDto $serviceDto,
        private readonly array $cars,
        private readonly Collection $detailOptions,
        private readonly ?DateTimeImmutable $serviceDate,
        private readonly ?BookingPeriodDto $period,
    ) {}

    public function toArray(): array
    {
        $guestCount = collect($this->cars)->reduce(
            fn(int $value, CarDto $carDto) => $value + $carDto->passengersCount + $carDto->babyCount,
            0
        );

        return [
            'cars' => $this->cars,
            'service' => $this->serviceDto,
            'guestsCount' => $guestCount,
            'detailOptions' => $this->detailOptions,
            'date' => $this->serviceDate?->format('d.m.Y'),
            'period' => $this->period,
        ];
    }
}
