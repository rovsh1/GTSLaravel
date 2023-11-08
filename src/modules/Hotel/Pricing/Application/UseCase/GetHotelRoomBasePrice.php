<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Application\UseCase;

use DateTimeInterface;
use Module\Hotel\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Hotel\Pricing\Infrastructure\Service\HotelRoomBaseDayValueFinder;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetHotelRoomBasePrice implements UseCaseInterface
{
    public function __construct(
        private readonly HotelRoomBaseDayValueFinder $domainUseCase
    ) {}

    public function execute(
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date,
    ): ?float {
        return $this->domainUseCase->find(
            new RoomId($roomId),
            $rateId,
            $isResident,
            $guestsCount,
            $date,
        );
    }
}
