<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Application\Dto\AddGuestDto;
use Module\Booking\Application\Dto\GuestDto;
use Module\Booking\Application\Dto\OrderAvailableActionsDto;
use Module\Booking\Application\Dto\OrderDto;
use Module\Booking\Application\Dto\UpdateGuestDto;
use Module\Booking\Application\UseCase\Admin\Order\GetActiveOrders;
use Module\Booking\Application\UseCase\Admin\Order\GetAvailableActions;
use Module\Booking\Application\UseCase\Admin\Order\GetOrder;
use Module\Booking\Application\UseCase\Admin\Order\GetOrderBookings;
use Module\Booking\Application\UseCase\Admin\Order\GetStatuses;
use Module\Booking\Application\UseCase\Admin\Order\Guest\Add;
use Module\Booking\Application\UseCase\Admin\Order\Guest\Delete;
use Module\Booking\Application\UseCase\Admin\Order\Guest\Get;
use Module\Booking\Application\UseCase\Admin\Order\Guest\Update;

class OrderAdapter
{
    public function getActiveOrders(int|null $clientId = null): array
    {
        return app(GetActiveOrders::class)->execute($clientId);
    }

    public function findOrder(int $id): ?OrderDto
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

    public function getStatuses(): array
    {
        return app(GetStatuses::class)->execute();
    }

    public function getAvailableActions(int $orderId): OrderAvailableActionsDto
    {
        return app(GetAvailableActions::class)->execute($orderId);
    }

    public function getBookings(int $orderId): array
    {
        return app(GetOrderBookings::class)->execute($orderId);
    }
}
