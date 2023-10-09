<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Application\Admin\ServiceBooking\Request\CreateBookingDto;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\BulkDeleteBookings;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\CopyBooking;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\CreateBooking;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\DeleteBooking;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\GetAvailableActions;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\GetBooking;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\GetBookingQuery;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\GetStatuses;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\GetStatusHistory;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\UpdateBookingStatus;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\UpdateNote;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\ServiceTypeEnum;

class ServiceAdapter
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
        int $clientId,
        int|null $legalId,
        CurrencyEnum $currency,
        ServiceTypeEnum $serviceType,
        int $creatorId,
        ?int $orderId,
        ?string $note = null
    ) {
        return app(CreateBooking::class)->execute(
            new CreateBookingDto(
                creatorId: $creatorId,
                clientId: $clientId,
                legalId: $legalId,
                currency: $currency,
                serviceType: $serviceType,
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
