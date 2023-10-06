<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\Response;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Module\Booking\Application\AirportBooking\Response\Details\AirportInfoDto;
use Module\Booking\Application\AirportBooking\Response\Details\ServiceInfoDto;
use Module\Booking\Application\Shared\Response\BookingDto as BaseDto;
use Module\Booking\Application\Shared\Response\BookingPriceDto;
use Module\Booking\Application\Shared\Response\StatusDto;
use Module\Booking\Application\HotelBooking\Dto\Details\CancelConditionsDto;

class BookingDto extends BaseDto
{
    public function __construct(
        int $id,
        StatusDto $status,
        int $orderId,
        CarbonImmutable $createdAt,
        int $creatorId,
        public readonly ?string $note,
        public readonly AirportInfoDto $airportInfo,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CarbonInterface $date,
        public readonly array $guestIds,
        public readonly CancelConditionsDto $cancelConditions,
        public readonly BookingPriceDto $price,
        public readonly string $flightNumber,
    ) {
        parent::__construct($id, $status, $orderId, $createdAt, $creatorId);
    }
}
