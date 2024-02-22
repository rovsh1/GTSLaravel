<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

final class RoomPriceNotFoundException extends ApplicationException
{
    protected $code = self::HOTEL_ROOM_PRICE_NOT_FOUND;
}
