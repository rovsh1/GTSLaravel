<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Carbon\CarbonPeriod;
use Module\Booking\Moderation\Application\Dto\GuestDto;
use Module\Booking\Moderation\Application\Dto\OrderAvailableActionsDto;
use Module\Booking\Moderation\Application\Dto\OrderDto;
use Module\Booking\Moderation\Application\Dto\UpdateGuestDto;
use Module\Booking\Moderation\Application\RequestDto\AddGuestRequestDto;
use Module\Booking\Moderation\Application\RequestDto\CreateOrderRequestDto;
use Module\Booking\Moderation\Application\UseCase\Order\CreateOrder;
use Module\Booking\Moderation\Application\UseCase\Order\GetActiveOrders;
use Module\Booking\Moderation\Application\UseCase\Order\GetAvailableActions;
use Module\Booking\Moderation\Application\UseCase\Order\GetOrder;
use Module\Booking\Moderation\Application\UseCase\Order\GetOrderBookings;
use Module\Booking\Moderation\Application\UseCase\Order\GetStatuses;
use Module\Booking\Moderation\Application\UseCase\Order\Guest\Add;
use Module\Booking\Moderation\Application\UseCase\Order\Guest\Delete;
use Module\Booking\Moderation\Application\UseCase\Order\Guest\Get;
use Module\Booking\Moderation\Application\UseCase\Order\Guest\Update;
use Module\Booking\Moderation\Application\UseCase\Order\UpdateStatus;
use Sdk\Shared\Enum\CurrencyEnum;

class OrderAdapter
{
    public function getOrder(int $id): ?OrderDto
    {
        return app(GetOrder::class)->execute($id);
    }

    public function getActiveOrders(int|null $clientId = null): array
    {
        return app(GetActiveOrders::class)->execute($clientId);
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
            new AddGuestRequestDto(
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

    public function updateStatus(int $orderId, int $status): void
    {
        app(UpdateStatus::class)->execute($orderId, $status);
    }

    public function create(
        int $clientId,
        int|null $legalId,
        CurrencyEnum $currency,
        CarbonPeriod $period,
        int $managerId,
        int $creatorId
    ): int {
        return app(CreateOrder::class)->execute(
            new CreateOrderRequestDto(
                $clientId,
                $legalId,
                $currency,
                $period,
                $managerId,
                $creatorId
            )
        );
    }
}
