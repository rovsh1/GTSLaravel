<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Illuminate\Support\Facades\DB;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\BookingDto;
use Module\Booking\Moderation\Application\RequestDto\CreateBookingRequestDto;
use Module\Booking\Moderation\Application\UseCase\BulkDeleteBookings;
use Module\Booking\Moderation\Application\UseCase\CopyBooking;
use Module\Booking\Moderation\Application\UseCase\CreateBooking;
use Module\Booking\Moderation\Application\UseCase\DeleteBooking;
use Module\Booking\Moderation\Application\UseCase\GetAvailableActions as ModerationActions;
use Module\Booking\Moderation\Application\UseCase\UpdateNote;
use Module\Booking\Moderation\Application\UseCase\UpdateStatus;
use Pkg\Booking\Common\Application\UseCase\GetBooking;
use Pkg\Booking\Common\Application\UseCase\GetStatuses;
use Pkg\Booking\EventSourcing\Application\UseCase\GetStatusHistory;
use Pkg\Booking\Requesting\Application\UseCase\GetAvailableActions as RequestingActions;
use Sdk\Shared\Enum\CurrencyEnum;

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
        int $managerId,
        int $creatorId,
        ?int $orderId,
        ?array $detailsData,
        ?string $note = null
    ) {
        $request = new CreateBookingRequestDto(
            clientId: $clientId,
            legalId: $legalId,
            currency: $currency,
            serviceId: $serviceId,
            hotelId: null,
            administratorId: $managerId,
            creatorId: $creatorId,
            orderId: $orderId,
            detailsData: $detailsData,
            note: $note
        );

        return DB::transaction(fn() => app(CreateBooking::class)->execute($request));
    }

    public function createHotelBooking(
        int $clientId,
        int|null $legalId,
        CurrencyEnum $currency,
        int $hotelId,
        int $managerId,
        int $creatorId,
        ?int $orderId,
        ?array $detailsData,
        ?string $note = null
    ) {
        $request = new CreateBookingRequestDto(
            clientId: $clientId,
            legalId: $legalId,
            currency: $currency,
            serviceId: null,
            hotelId: $hotelId,
            administratorId: $managerId,
            creatorId: $creatorId,
            orderId: $orderId,
            detailsData: $detailsData,
            note: $note
        );

        return DB::transaction(fn() => app(CreateBooking::class)->execute($request));
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

    public function copyBooking(int $id): BookingDto
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
