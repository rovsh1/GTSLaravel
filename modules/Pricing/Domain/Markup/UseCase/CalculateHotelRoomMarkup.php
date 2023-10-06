<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\UseCase;

use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Pricing\Domain\Markup\Service\HotelMarkupFinderInterface;
use Module\Pricing\Domain\Shared\ValueObject\ClientId;

final class CalculateHotelRoomMarkup
{
    public function __construct(
        private readonly HotelMarkupFinderInterface $hotelMarkupFinder
    ) {
    }

    public function execute(int|float $price, ClientId $clientId, ?RoomId $roomId): int
    {
        $markup = $this->hotelMarkupFinder->findByRoomId($clientId, $roomId);

        return $markup->calculate($price);
    }
}