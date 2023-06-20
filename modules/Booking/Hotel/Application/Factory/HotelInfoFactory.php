<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Factory;

use Module\Booking\Hotel\Domain\ValueObject\Details\HotelInfo;
use Module\Shared\Domain\ValueObject\Time;

class HotelInfoFactory
{
    public static function fromDto(mixed $hotelDto): HotelInfo
    {
        return new HotelInfo(
            $hotelDto->id,
            $hotelDto->name,
            new Time('14:00'), //@todo забрать из настроек отеля
            new Time('12:00'),
        );
    }
}
