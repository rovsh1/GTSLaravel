<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Transfer\Application\Request\CreateBookingDto;
use Module\Booking\Transfer\Application\UseCase\Admin\BulkDeleteBookings;
use Module\Booking\Transfer\Application\UseCase\Admin\CopyBooking;
use Module\Booking\Transfer\Application\UseCase\Admin\CreateBooking;
use Module\Booking\Transfer\Application\UseCase\Admin\DeleteBooking;
use Module\Booking\Transfer\Application\UseCase\Admin\GetAvailableActions;
use Module\Booking\Transfer\Application\UseCase\Admin\GetBooking;
use Module\Booking\Transfer\Application\UseCase\Admin\GetBookingQuery;
use Module\Booking\Transfer\Application\UseCase\Admin\GetStatuses;
use Module\Booking\Transfer\Application\UseCase\Admin\GetStatusHistory;
use Module\Booking\Transfer\Application\UseCase\Admin\UpdateBookingStatus;
use Module\Booking\Transfer\Application\UseCase\Admin\UpdateNote;

class TransferAdapter
{
    public function getBooking(int $id): mixed
    {
        return app(GetBooking::class)->execute($id);
    }

    public function getBookingQuery(): Builder
    {
        return app(GetBookingQuery::class)->execute();
    }

    public function getStatuses(): array
    {
        return app(GetStatuses::class)->execute();
    }

    public function createBooking(
        int $cityId,
        int $clientId,
        int|null $legalId,
        int $currencyId,
        int $serviceId,
        int $creatorId,
        ?int $orderId,
        ?string $note = null
    ) {
        return app(CreateBooking::class)->execute(
            new CreateBookingDto(
                cityId: $cityId,
                creatorId: $creatorId,
                clientId: $clientId,
                legalId: $legalId,
                currencyId: $currencyId,
                serviceId: $serviceId,
                orderId: $orderId,
                note: $note
            )
        );
    }

    public function getAvailableActions(int $id): array
    {
        return app(GetAvailableActions::class)->execute($id);
    }

    public function updateStatus(
        int $id,
        int $status,
        string|null $notConfirmedReason = null,
        float|null $cancelFeeAmount = null
    ): mixed {
        return app(UpdateBookingStatus::class)->execute($id, $status, $notConfirmedReason, $cancelFeeAmount);
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
