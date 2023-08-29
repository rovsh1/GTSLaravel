<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Order\Application\Request\AddTouristDto;
use Module\Booking\Order\Application\Request\UpdateTouristDto;
use Module\Booking\Order\Application\Response\TouristDto;
use Module\Booking\Order\Application\UseCase\Admin\GetActiveOrders;
use Module\Booking\Order\Application\UseCase\Admin\GetOrder;
use Module\Booking\Order\Application\UseCase\Admin\Tourist\Add;
use Module\Booking\Order\Application\UseCase\Admin\Tourist\Delete;
use Module\Booking\Order\Application\UseCase\Admin\Tourist\Get;
use Module\Booking\Order\Application\UseCase\Admin\Tourist\Update;

class OrderAdapter
{
    public function getActiveOrders(int|null $clientId = null): array
    {
        return app(GetActiveOrders::class)->execute($clientId);
    }

    public function findOrder(int $id): mixed
    {
        return app(GetOrder::class)->execute($id);
    }

    public function addTourist(
        int $orderId,
        string $fullName,
        int $countryId,
        int $gender,
        bool $isAdult,
        int|null $age
    ): TouristDto {
        return app(Add::class)->execute(
            new AddTouristDto(
                orderId: $orderId,
                fullName: $fullName,
                countryId: $countryId,
                gender: $gender,
                isAdult: $isAdult,
                age: $age,
            )
        );
    }

    public function updateTourist(
        int $touristId,
        string $fullName,
        int $countryId,
        int $gender,
        bool $isAdult,
        int|null $age
    ): bool {
        return app(Update::class)->execute(
            new UpdateTouristDto(
                touristId: $touristId,
                fullName: $fullName,
                countryId: $countryId,
                gender: $gender,
                isAdult: $isAdult,
                age: $age,
            )
        );
    }

    public function deleteTourist(int $touristId): void
    {
        app(Delete::class)->execute($touristId);
    }

    public function getTourists(int $orderId): array
    {
        return app(Get::class)->execute($orderId);
    }
}
