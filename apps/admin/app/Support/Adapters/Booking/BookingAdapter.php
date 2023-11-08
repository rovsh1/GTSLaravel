<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Moderation\Application\RequestDto\CreateBookingRequestDto;
use Module\Booking\Moderation\Application\UseCase\BulkDeleteBookings;
use Module\Booking\Moderation\Application\UseCase\CopyBooking;
use Module\Booking\Moderation\Application\UseCase\CreateBooking;
use Module\Booking\Moderation\Application\UseCase\DeleteBooking;
use Module\Booking\Moderation\Application\UseCase\GetAvailableActions;
use Module\Booking\Moderation\Application\UseCase\GetBooking;
use Module\Booking\Moderation\Application\UseCase\GetStatuses;
use Module\Booking\Moderation\Application\UseCase\GetStatusHistory;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\CreateBooking as CreateHotelBooking;
use Module\Booking\Moderation\Application\UseCase\UpdateBookingStatus;
use Module\Booking\Moderation\Application\UseCase\UpdateNote;
use Module\Shared\Enum\CurrencyEnum;

class BookingAdapter
{
    public function getBooking(int $id): mixed
    {
        return app(GetBooking::class)->execute($id);
    }

    public function getStatuses(): array
    {
        return app(GetStatuses::class)->execute();
    }

    public function createBooking(
        int $clientId,
        int|null $legalId,
        CurrencyEnum $currency,
        int $serviceId,
        int $creatorId,
        ?int $orderId,
        ?array $detailsData,
        ?string $note = null
    ) {
        return app(CreateBooking::class)->execute(
            new CreateBookingRequestDto(
                creatorId: $creatorId,
                clientId: $clientId,
                legalId: $legalId,
                currency: $currency,
                serviceId: $serviceId,
                orderId: $orderId,
                detailsData: $detailsData,
                note: $note
            )
        );
    }

    public function createHotelBooking(
        int $clientId,
        int|null $legalId,
        CurrencyEnum $currency,
        int $hotelId,
        int $creatorId,
        ?int $orderId,
        ?array $detailsData,
        ?string $note = null
    ) {
        return app(CreateHotelBooking::class)->execute(
            new CreateBookingRequestDto(
                creatorId: $creatorId,
                clientId: $clientId,
                legalId: $legalId,
                currency: $currency,
                serviceId: $hotelId,
                orderId: $orderId,
                detailsData: $detailsData,
                note: $note
            )
        );
    }

    public function getAvailableActions(int $id): mixed
    {
        return app(GetAvailableActions::class)->execute($id);
    }

    public function updateStatus(
        int $id,
        int $status,
        string|null $notConfirmedReason = null,
        float|null $supplierPenalty = null,
        float|null $clientPenalty = null,
    ): mixed {
        return app(UpdateBookingStatus::class)->execute($id, $status, $notConfirmedReason, $supplierPenalty, $clientPenalty);
    }

    public function getStatusHistory(int $id): array
    {
        return app(GetStatusHistory::class)->execute($id);
    }

    public function updateNote(int $bookingId, string|null $note): void
    {
        app(UpdateNote::class)->execute($bookingId, $note);
    }

    public function copyBooking(int $id): int
    {
        return app(CopyBooking::class)->execute($id);
    }

    public function deleteBooking(int $id): void
    {
        app(DeleteBooking::class)->execute($id);
    }

    public function bulkDeleteBookings(array $ids): void
    {
        app(BulkDeleteBookings::class)->execute($ids);
    }
}
