<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Application\Admin\Order\Request\AddGuestDto;
use Module\Booking\Application\Admin\Order\Request\UpdateGuestDto;
use Module\Booking\Application\Admin\Order\Response\GuestDto;
use Module\Booking\Application\Admin\Order\UseCase\GetActiveOrders;
use Module\Booking\Application\Admin\Order\UseCase\GetOrder;
use Module\Booking\Application\Admin\Order\UseCase\Guest\Add;
use Module\Booking\Application\Admin\Order\UseCase\Guest\Delete;
use Module\Booking\Application\Admin\Order\UseCase\Guest\Get;
use Module\Booking\Application\Admin\Order\UseCase\Guest\Update;

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

    public function addGuest(
        int $orderId,
        string $fullName,
        int $countryId,
        int $gender,
        bool $isAdult,
        int|null $age
    ): GuestDto {
        return app(Add::class)->execute(
            new AddGuestDto(
                orderId: $orderId,
                fullName: $fullName,
                countryId: $countryId,
                gender: $gender,
                isAdult: $isAdult,
                age: $age,
            )
        );
    }

    public function updateGuest(
        int $guestId,
        string $fullName,
        int $countryId,
        int $gender,
        bool $isAdult,
        int|null $age
    ): bool {
        return app(Update::class)->execute(
            new UpdateGuestDto(
                guestId: $guestId,
                fullName: $fullName,
                countryId: $countryId,
                gender: $gender,
                isAdult: $isAdult,
                age: $age,
            )
        );
    }

    public function deleteGuest(int $guestId): void
    {
        app(Delete::class)->execute($guestId);
    }

    public function getGuests(int $orderId): array
    {
        return app(Get::class)->execute($orderId);
    }
}
