<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\Dto;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Module\Booking\Airport\Application\Dto\Details\AirportInfoDto;
use Module\Booking\Airport\Application\Dto\Details\ServiceInfoDto;
use Module\Booking\Airport\Domain\Entity\Booking;
use Module\Booking\Common\Application\Response\BookingDto as BaseDto;
use Module\Booking\Common\Application\Response\StatusDto;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\HotelBooking\Application\Dto\BookingPriceDto;
use Module\Booking\HotelBooking\Application\Dto\Details\CancelConditionsDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

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
