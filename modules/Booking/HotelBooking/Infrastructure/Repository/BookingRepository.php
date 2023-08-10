<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Infrastructure\Repository;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Common\Infrastructure\Repository\AbstractBookingRepository as BaseRepository;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\CancelConditions;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\HotelInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Booking\HotelBooking\Infrastructure\Models\Booking as Model;
use Module\Booking\HotelBooking\Infrastructure\Models\BookingDetails;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    public function __construct(
        private readonly RoomBookingRepositoryInterface $roomBookingRepository
    ) {}

    protected function getModel(): string
    {
        return Model::class;
    }

    public function find(int $id): ?Booking
    {
        $booking = $this->findBase($id);
        if ($booking === null) {
            return null;
        }
        $detailsModel = BookingDetails::whereBookingId($id)->first();
        $roomBookings = $this->roomBookingRepository->get($id);

        return $this->buildEntityFromModel($booking, $detailsModel, $roomBookings);
    }

    public function get(): Collection
    {
        return $this->getModel()::query()->withDetails()->get();
    }

    public function query(): Builder
    {
        return $this->getModel()::query()->withDetails();
    }

    public function create(
        OrderId $orderId,
        Id $creatorId,
        BookingPeriod $period,
        ?string $note = null,
        HotelInfo $hotelInfo,
        CancelConditions $cancelConditions,
        QuotaProcessingMethodEnum $quotaProcessingMethod,
    ): Booking {
        return \DB::transaction(
            function () use (
                $orderId,
                $creatorId,
                $period,
                $note,
                $hotelInfo,
                $cancelConditions,
                $quotaProcessingMethod
            ) {
                $bookingModel = $this->createBase($orderId, $creatorId->value());
                $booking = new Booking(
                    id: new BookingId($bookingModel->id),
                    orderId: $orderId,
                    status: $bookingModel->status,
                    createdAt: $bookingModel->created_at->toImmutable(),
                    creatorId: $creatorId,
                    roomBookings: new RoomBookingCollection(),
                    cancelConditions: $cancelConditions,
                    additionalInfo: null,
                    hotelInfo: $hotelInfo,
                    period: $period,
                    note: $note,
                    price: BookingPrice::fromData($bookingModel->price),
                    quotaProcessingMethod: $quotaProcessingMethod,
                );

                BookingDetails::create([
                    'booking_id' => $booking->id()->value(),
                    'hotel_id' => $hotelInfo->id(),
                    'date_start' => $period->dateFrom(),
                    'date_end' => $period->dateTo(),
                    'nights_count' => $period->nightsCount(),
                    'quota_processing_method' => $quotaProcessingMethod,
                    'data' => $this->serializeDetails($booking)
                ]);

                return $booking;
            }
        );
    }

    public function store(Booking $booking): bool
    {
        return \DB::transaction(function () use ($booking) {
            $base = $this->storeBase($booking);

            foreach ($booking->roomBookings() as $roomBooking) {
                $this->roomBookingRepository->store($roomBooking);
            }

            $details = (bool)BookingDetails::whereBookingId($booking->id()->value())
                ->update([
                    'hotel_id' => $booking->hotelInfo()->id(),
                    'date_start' => $booking->period()->dateFrom(),
                    'date_end' => $booking->period()->dateTo(),
                    'nights_count' => $booking->period()->nightsCount(),
                    'quota_processing_method' => $booking->quotaProcessingMethod(),
                    'data' => $this->serializeDetails($booking)
                ]);

            return $base && $details;
        });
    }

    public function searchByDateUpdate(CarbonInterface $dateUpdate, ?int $hotelId): array
    {
        $modelsQuery = $this->getModel()::query()
            ->withClient()
            ->where('updated_at', '>=', $dateUpdate);

        if ($hotelId !== null) {
            $modelsQuery->where('hotel_id', $hotelId);
        }

        $models = $modelsQuery->get();

        return app(BookingFactory::class)->createCollectionFrom($models);
    }

    public function searchActive(?int $hotelId): array
    {
        $modelsQuery = $this->getModel()::query()
            ->withClient();
        //todo уточнить по поводу статуса у Анвара
//            ->where('reservation.status', BookingStatusEnum::WaitingConfirmation);

        if ($hotelId !== null) {
            $modelsQuery->where('hotel_id', $hotelId);
        }

        $models = $modelsQuery->get();

        return app(BookingFactory::class)->createCollectionFrom($models);
    }

    public function delete(int $id): void
    {
        $this->getModel()::query()->whereId($id)->delete();
    }

    /**
     * @param int[] $ids
     * @return void
     */
    public function bulkDelete(array $ids): void
    {
        $this->getModel()::query()->whereIn('id', $ids)->delete();
    }


    private function buildEntityFromModel(
        Model $booking,
        BookingDetails $detailsModel,
        RoomBookingCollection $roomBookings,
    ): Booking {
        $detailsData = $detailsModel->data;
        $additionalInfo = $detailsData['additionalInfo'] ?? null;

        return new Booking(
            id: new BookingId($booking->id),
            orderId: new OrderId($booking->order_id),
            status: $booking->status,
            createdAt: $booking->created_at->toImmutable(),
            creatorId: new Id($booking->creator_id),
            note: $detailsData['note'] ?? null,
            hotelInfo: HotelInfo::fromData($detailsData['hotelInfo']),
            period: BookingPeriod::fromData($detailsData['period']),
            additionalInfo: $additionalInfo !== null ? AdditionalInfo::fromData($detailsData['additionalInfo']) : null,
            roomBookings: $roomBookings,
            cancelConditions: CancelConditions::fromData($detailsData['cancelConditions']),
            price: BookingPrice::fromData($booking->price),
            quotaProcessingMethod: $detailsModel->quota_processing_method
        );
    }

    private function serializeDetails(Booking $booking): array
    {
        return [
            'note' => $booking->note(),
            'hotelInfo' => $booking->hotelInfo()->toData(),
            'additionalInfo' => $booking->additionalInfo()?->toData(),
            'period' => $booking->period()->toData(),
            'cancelConditions' => $booking->cancelConditions()->toData(),
        ];
    }
}
