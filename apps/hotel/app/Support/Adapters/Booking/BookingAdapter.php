<?php

declare(strict_types=1);

namespace App\Hotel\Support\Adapters\Booking;

use Module\Booking\EventSourcing\Application\UseCase\GetStatusHistory;
use Module\Booking\Moderation\Application\UseCase\GetAvailableActions as ModerationActions;
use Module\Booking\Moderation\Application\UseCase\GetBooking;
use Module\Booking\Moderation\Application\UseCase\GetStatuses;
use Module\Booking\Moderation\Application\UseCase\UpdateNote;
use Module\Booking\Moderation\Application\UseCase\UpdateStatus;
use Module\Booking\Requesting\Application\UseCase\GetAvailableActions as RequestingActions;

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
        $moderationAction = app(ModerationActions::class)->execute($id);
        $requestingActions = app(RequestingActions::class)->execute($id);

        return array_merge((array)$moderationAction, (array)$requestingActions);
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

    public function getStatusHistory(int $id): array
    {
        return app(GetStatusHistory::class)->execute($id);
    }

    public function updateNote(int $bookingId, string|null $note): void
    {
        app(UpdateNote::class)->execute($bookingId, $note);
    }
}
