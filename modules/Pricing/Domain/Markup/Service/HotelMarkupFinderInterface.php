<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\Service;

use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Pricing\Domain\Shared\ValueObject\ClientId;
use Module\Shared\ValueObject\MarkupValue;

interface HotelMarkupFinderInterface
{
    public function findByRoomId(ClientId $clientId, RoomId $roomId): MarkupValue;
}
