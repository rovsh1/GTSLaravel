<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use App\Admin\Http\Requests\Order\Guest\AddRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Module\Booking\Application\Admin\Booking\Request\CreateBookingRequestDto;
use Module\Booking\Application\Admin\Booking\UseCase\CreateBooking;
use Module\Booking\Application\Admin\ServiceBooking\Dto\CarBidDataDto;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\BulkDeleteBookings;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid\Remove;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid\Update;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\CopyBooking;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\DeleteBooking;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\GetAvailableActions;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\GetBooking;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\GetBookingQuery;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\GetStatuses;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\GetStatusHistory;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\Guest\Bind;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\UpdateBookingStatus;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\UpdateDetailsField;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\UpdateNote;
use Module\Booking\Application\AirportBooking\UseCase\Admin\Guest\Unbind;
use Module\Shared\Enum\CurrencyEnum;

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

    public function updateDetailsField(int $bookingId, string $field, mixed $value): void
    {
        app(UpdateDetailsField::class)->execute($bookingId, $field, $value);
    }

    public function bindGuest(int $bookingId, int $guestId): void
    {
        app(Bind::class)->execute($bookingId, $guestId);
    }

    public function unbindGuest(int $bookingId, int $guestId): void
    {
        app(Unbind::class)->execute($bookingId, $guestId);
    }

    public function addCarBid(int $bookingId, array $carData): void
    {
        app(AddRequest::class)->execute(
            $bookingId,
            $this->buildCarBidDto($carData)
        );
    }

    public function updateCarBid(int $bookingId, string $carBidId, array $carData): void
    {
        app(Update::class)->execute(
            $bookingId,
            $carBidId,
            $this->buildCarBidDto($carData)
        );
    }

    public function removeCarBid(int $bookingId, string $carBidId): void
    {
        app(Remove::class)->execute($bookingId, $carBidId);
    }

    private function buildCarBidDto(array $data): CarBidDataDto
    {
        if (!Arr::has($data, ['carId', 'carsCount', 'passengersCount', 'baggageCount', 'babyCount'])) {
            throw new \InvalidArgumentException('Invalid car bid');
        }

        return new CarBidDataDto(
            $data['carId'],
            $data['carsCount'],
            $data['passengersCount'],
            $data['baggageCount'],
            $data['babyCount'],
        );
    }
}
