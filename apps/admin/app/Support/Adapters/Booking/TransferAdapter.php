<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Application\TransferBooking\Request\CreateBookingDto;
use Module\Booking\Application\TransferBooking\UseCase\Admin\BulkDeleteBookings;
use Module\Booking\Application\TransferBooking\UseCase\Admin\CopyBooking;
use Module\Booking\Application\TransferBooking\UseCase\Admin\CreateBooking;
use Module\Booking\Application\TransferBooking\UseCase\Admin\DeleteBooking;
use Module\Booking\Application\TransferBooking\UseCase\Admin\GetAvailableActions;
use Module\Booking\Application\TransferBooking\UseCase\Admin\GetBooking;
use Module\Booking\Application\TransferBooking\UseCase\Admin\GetBookingQuery;
use Module\Booking\Application\TransferBooking\UseCase\Admin\GetStatuses;
use Module\Booking\Application\TransferBooking\UseCase\Admin\GetStatusHistory;
use Module\Booking\Application\TransferBooking\UseCase\Admin\UpdateBookingStatus;
use Module\Booking\Application\TransferBooking\UseCase\Admin\UpdateNote;

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

    public function getAvailableActions(int $id): mixed
    {
        return app(GetAvailableActions::class)->execute($id);
    }

    public function updateStatus(
        int $id,
        int $status,
        string|null $notConfirmedReason = null,
        float|null $netPenalty = null
    ): mixed {
        return app(UpdateBookingStatus::class)->execute($id, $status, $notConfirmedReason, $netPenalty);
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
