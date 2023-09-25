<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Airport\Application\Request\CreateBookingDto;
use Module\Booking\Airport\Application\UseCase\Admin\BulkDeleteBookings;
use Module\Booking\Airport\Application\UseCase\Admin\CopyBooking;
use Module\Booking\Airport\Application\UseCase\Admin\CreateBooking;
use Module\Booking\Airport\Application\UseCase\Admin\DeleteBooking;
use Module\Booking\Airport\Application\UseCase\Admin\GetAvailableActions;
use Module\Booking\Airport\Application\UseCase\Admin\GetBooking;
use Module\Booking\Airport\Application\UseCase\Admin\GetBookingsByFilters;
use Module\Booking\Airport\Application\UseCase\Admin\GetStatuses;
use Module\Booking\Airport\Application\UseCase\Admin\GetStatusHistory;
use Module\Booking\Airport\Application\UseCase\Admin\Guest\Bind;
use Module\Booking\Airport\Application\UseCase\Admin\Guest\Unbind;
use Module\Booking\Airport\Application\UseCase\Admin\UpdateBookingStatus;
use Module\Booking\Airport\Application\UseCase\Admin\UpdateNote;
use Module\Booking\Airport\Application\UseCase\GetBookingQuery;

class AirportAdapter
{
    public function getBookingQuery(): Builder
    {
        return app(GetBookingQuery::class)->execute();
    }

    public function getBookings(array $filters = []): mixed
    {
        return app(GetBookingsByFilters::class)->execute($filters);
    }

    public function getBooking(int $id): mixed
    {
        return app(GetBooking::class)->execute($id);
    }

    public function createBooking(
        int $cityId,
        int $clientId,
        ?int $legalId,
        int $currencyId,
        int $airportId,
        int $serviceId,
        CarbonInterface $date,
        string $flightNumber,
        int $creatorId,
        ?int $orderId,
        ?string $note = null
    ): int {
        return app(CreateBooking::class)->execute(
            new CreateBookingDto(
                cityId: $cityId,
                creatorId: $creatorId,
                clientId: $clientId,
                legalId: $legalId,
                currencyId: $currencyId,
                airportId: $airportId,
                serviceId: $serviceId,
                orderId: $orderId,
                date: $date,
                flightNumber: $flightNumber,
                note: $note
            )
        );
    }

    public function bindGuest(int $bookingId, int $guestId): void
    {
        app(Bind::class)->execute($bookingId, $guestId);
    }

    public function unbindGuest(int $bookingId, int $guestId): void
    {
        app(Unbind::class)->execute($bookingId, $guestId);
    }

    public function getAvailableActions(int $id): mixed
    {
        return app(GetAvailableActions::class)->execute($id);
    }

    public function getStatuses(): array
    {
        return app(GetStatuses::class)->execute();
    }

    public function updateStatus(
        int $id,
        int $status,
        ?string $notConfirmedReason = null,
        ?float $cancelFeeAmount = null
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

    /**
     * @param int[] $ids
     * @return void
     */
    public function bulkDeleteBookings(array $ids): void
    {
        app(BulkDeleteBookings::class)->execute($ids);
    }
}
