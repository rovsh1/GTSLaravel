<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\Response;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Module\Booking\Airport\Application\Response\Details\AirportInfoDto;
use Module\Booking\Airport\Application\Response\Details\ServiceInfoDto;
use Module\Booking\Common\Application\Response\BookingDto as BaseDto;
use Module\Booking\Common\Application\Response\BookingPriceDto;
use Module\Booking\Common\Application\Response\StatusDto;
use Module\Booking\HotelBooking\Application\Dto\Details\CancelConditionsDto;

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
