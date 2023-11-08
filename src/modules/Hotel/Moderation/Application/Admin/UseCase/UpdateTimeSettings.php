<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\UseCase;

use Module\Hotel\Moderation\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\TimeSettings;
use Module\Shared\ValueObject\Time;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateTimeSettings implements UseCaseInterface
{
    public function __construct(
        private readonly HotelRepositoryInterface $repository
    ) {}

    public function execute(
        int $hotelId,
        string $checkInAfter,
        string $checkOutBefore,
        ?string $breakfastPeriodFrom,
        ?string $breakfastPeriodTo
    ): void {
        $hotel = $this->repository->find($hotelId);
        $breakfastPeriod = null;
        if ($breakfastPeriodFrom !== null && $breakfastPeriodTo !== null) {
            $breakfastPeriod = new TimeSettings\BreakfastPeriod(
                $breakfastPeriodFrom,
                $breakfastPeriodTo,
            );
        }
        $timeSettings = new TimeSettings(
            checkInAfter: new Time($checkInAfter),
            checkOutBefore: new Time($checkOutBefore),
            breakfastPeriod: $breakfastPeriod
        );

        $hotel->setTimeSettings($timeSettings);

        $this->repository->store($hotel);
    }
}
