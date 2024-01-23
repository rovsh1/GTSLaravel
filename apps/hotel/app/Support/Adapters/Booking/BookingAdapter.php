<?php

declare(strict_types=1);

namespace App\Hotel\Support\Adapters\Booking;

use Module\Booking\Moderation\Application\UseCase\UpdateNote;
use Module\Booking\Moderation\Application\UseCase\UpdateStatus;
use Pkg\Booking\Common\Application\UseCase\GetBooking;
use Pkg\Booking\Common\Application\UseCase\GetStatuses;
use Pkg\Booking\EventSourcing\Application\UseCase\GetStatusHistory;
use Pkg\Hotel\Administration\Application\UseCase\GetAvailableActions;
use Pkg\Hotel\Administration\Application\UseCase\SetBookingNoCheckIn;

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
        return app(UpdateStatus::class)->execute($id, $status, $notConfirmedReason, $supplierPenalty, $clientPenalty);
    }

    public function setNoCheckIn(int $id, float|null $supplierPenalty = null): mixed
    {
        return app(SetBookingNoCheckIn::class)->execute($id, $supplierPenalty);
    }

    public function getStatusHistory(int $id): array
    {
        return app(GetStatusHistory::class)->execute($id);
    }

    public function updateNote(int $bookingId, string|null $note): void
    {
        app(UpdateNote::class)->execute($bookingId, $note);
    }
}
