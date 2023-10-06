<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\Service;

use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Pricing\Domain\Markup\ValueObject\ClientId;
use Module\Pricing\Domain\Markup\ValueObject\MarkupValue;

interface HotelMarkupFinderInterface
{
    public function findByRoomId(ClientId $clientId, RoomId $roomId): MarkupValue;
}
