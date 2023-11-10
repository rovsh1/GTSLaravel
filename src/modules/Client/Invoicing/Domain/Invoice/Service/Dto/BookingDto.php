<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Service\Dto;

use Illuminate\Support\Collection;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking\CancelConditionsDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking\CarDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking\GuestDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking\PriceDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking\RoomDto;

class BookingDto
{
    /**
     * @param string $number
     * @param string $serviceName
     * @param Collection $detailOptions
     * @param PriceDto $price
     * @param CancelConditionsDto|null $cancelConditions
     * @param RoomDto[]|null $rooms
     * @param CarDto[]|null $cars
     * @param GuestDto[]|null $guests
     */
    public function __construct(
        public readonly string $number,
        public readonly string $serviceName,
        public readonly Collection $detailOptions,
        public readonly PriceDto $price,
        public readonly ?CancelConditionsDto $cancelConditions,
        public readonly ?array $rooms,
        public readonly ?array $cars,
        public readonly ?array $guests,
    ) {}
}
