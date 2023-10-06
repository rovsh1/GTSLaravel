<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Carbon\CarbonPeriod;
use Module\Booking\Application\HotelBooking\Request\AddRoomDto;
use Module\Booking\Application\HotelBooking\Request\CreateBookingDto;
use Module\Booking\Application\HotelBooking\Request\UpdateBookingDto;
use Module\Booking\Application\HotelBooking\Request\UpdateRoomDto;
use Module\Booking\Application\HotelBooking\UseCase\Admin\BulkDeleteBookings;
use Module\Booking\Application\HotelBooking\UseCase\Admin\CopyBooking;
use Module\Booking\Application\HotelBooking\UseCase\Admin\CreateBooking;
use Module\Booking\Application\HotelBooking\UseCase\Admin\DeleteBooking;
use Module\Booking\Application\HotelBooking\UseCase\Admin\GetAvailableActions;
use Module\Booking\Application\HotelBooking\UseCase\Admin\GetBooking;
use Module\Booking\Application\HotelBooking\UseCase\Admin\GetBookingQuery;
use Module\Booking\Application\HotelBooking\UseCase\Admin\GetBookingsByFilters;
use Module\Booking\Application\HotelBooking\UseCase\Admin\GetStatuses;
use Module\Booking\Application\HotelBooking\UseCase\Admin\GetStatusHistory;
use Module\Booking\Application\HotelBooking\UseCase\Admin\Room\Add;
use Module\Booking\Application\HotelBooking\UseCase\Admin\Room\Delete;
use Module\Booking\Application\HotelBooking\UseCase\Admin\Room\Guest\Bind;
use Module\Booking\Application\HotelBooking\UseCase\Admin\Room\Guest\Unbind;
use Module\Booking\Application\HotelBooking\UseCase\Admin\Room\Update;
use Module\Booking\Application\HotelBooking\UseCase\Admin\UpdateBooking;
use Module\Booking\Application\HotelBooking\UseCase\Admin\UpdateBookingStatus;
use Module\Booking\Application\HotelBooking\UseCase\Admin\UpdateExternalNumber;
use Module\Booking\Application\HotelBooking\UseCase\Admin\UpdateNote;

class HotelAdapter
{
    public function getBookings(array $filters = []): mixed
    {
        return app(GetBookingsByFilters::class)->execute($filters);
    }

    public function getBookingQuery(): mixed
    {
        return app(GetBookingQuery::class)->execute();
    }

    public function getBooking(int $id): mixed
    {
        return app(GetBooking::class)->execute($id);
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

    public function createBooking(
        int $cityId,
        int $clientId,
        ?int $legalId,
        int $currencyId,
        int $hotelId,
        CarbonPeriod $period,
        int $creatorId,
        int $quotaProcessingMethod,
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
                hotelId: $hotelId,
                orderId: $orderId,
                period: $period,
                note: $note,
                quotaProcessingMethod: $quotaProcessingMethod,
            )
        );
    }

    public function updateBooking(
        int $id,
        CarbonPeriod $period,
        ?string $note = null
    ): void {
        app(UpdateBooking::class)->execute(
            new UpdateBookingDto(
                id: $id,
                period: $period,
                note: $note
            )
        );
    }

    public function getAvailableActions(int $id): mixed
    {
        return app(GetAvailableActions::class)->execute($id);
    }

    public function addRoom(
        int $bookingId,
        int $roomId,
        int $rateId,
        bool $isResident,
        array|null $earlyCheckIn = null,
        array|null $lateCheckOut = null,
        ?string $note = null,
        ?int $discount = null
    ): void {
        //TODO use AddRoom name
        app(Add::class)->execute(
            new AddRoomDto(
                bookingId: $bookingId,
                roomId: $roomId,
                rateId: $rateId,
                isResident: $isResident,
                earlyCheckIn: $earlyCheckIn,
                lateCheckOut: $lateCheckOut,
                note: $note,
                discount: $discount
            )
        );
    }

    public function updateRoom(
        int $bookingId,
        int $roomBookingId,
        int $roomId,
        int $rateId,
        bool $isResident,
        array|null $earlyCheckIn = null,
        array|null $lateCheckOut = null,
        ?string $note = null,
        ?int $discount = null
    ): void {
        app(Update::class)->execute(
            new UpdateRoomDto(
                bookingId: $bookingId,
                roomBookingId: $roomBookingId,
                roomId: $roomId,
                rateId: $rateId,
                isResident: $isResident,
                earlyCheckIn: $earlyCheckIn,
                lateCheckOut: $lateCheckOut,
                note: $note,
                discount: $discount
            )
        );
    }

    public function deleteRoom(int $bookingId, int $roomBookingId): void
    {
        app(Delete::class)->execute(
            $bookingId,
            $roomBookingId
        );
    }

    public function bindRoomGuest(int $bookingId, int $roomBookingId, int $guestId): void
    {
        app(Bind::class)->execute(
            bookingId: $bookingId,
            roomBookingId: $roomBookingId,
            guestId: $guestId
        );
    }

    public function unbindRoomGuest(int $bookingId, int $roomBookingId, int $guestId): void
    {
        app(Unbind::class)->execute(
            bookingId: $bookingId,
            roomBookingId: $roomBookingId,
            guestId: $guestId
        );
    }

    public function updateExternalNumber(int $bookingId, int $type, ?string $number): void
    {
        app(UpdateExternalNumber::class)->execute($bookingId, $type, $number);
    }

    public function updateNote(int $bookingId, string|null $note): void
    {
        app(UpdateNote::class)->execute($bookingId, $note);
    }

    public function getStatuses(): array
    {
        return app(GetStatuses::class)->execute();
    }

    public function updateStatus(
        int $id,
        int $status,
        ?string $notConfirmedReason = null,
        ?float $netPenalty = null
    ): mixed {
        return app(UpdateBookingStatus::class)->execute($id, $status, $notConfirmedReason, $netPenalty);
    }

    public function getStatusHistory(int $id): array
    {
        return app(GetStatusHistory::class)->execute($id);
    }
}
