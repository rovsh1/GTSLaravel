<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\TransferBooking;

use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\GuestDto;

class CarDto
{
    public function __construct(
        public readonly string $mark,
        public readonly string $model,
        public readonly int $carsCount,
        public readonly int $passengersCount,
        public readonly int $baggageCount,
        public readonly int $babyCount,
        public readonly CarPriceDto $supplierPrice,
        /** @var GuestDto[] */
        public readonly array $guests,
    ) {}
}
