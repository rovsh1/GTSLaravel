<?php

namespace Module\Booking\Domain\BookingRequest\Service\TemplateData\TransferBooking;

use Illuminate\Support\Collection;
use Module\Booking\Domain\BookingRequest\Service\Dto\ServiceDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking\BookingPeriodDto;
use Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking\CarDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;

final class BookingRequest implements TemplateDataInterface
{
    public function __construct(
        private readonly ServiceDto $serviceDto,
        private readonly array $cars,
        private readonly Collection $detailOptions,
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
            'period' => $this->period,
        ];
    }
}
