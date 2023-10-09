<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\HotelBooking\Repository;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Deprecated\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Deprecated\HotelBooking\ValueObject\Details\AdditionalInfo;
use Module\Booking\Deprecated\HotelBooking\ValueObject\Details\RoomBookingCollection;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Infrastructure\HotelBooking\Models\Booking as Model;
use Module\Booking\Infrastructure\HotelBooking\Models\BookingDetails;
use Module\Booking\Infrastructure\Shared\Repository\AbstractBookingRepository as BaseRepository;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    public function __construct(
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
    ) {}

    protected function getModel(): string
    {
        return Model::class;
    }

    public function find(int $id): ?HotelBooking
    {
        $booking = $this->findBase($id);
        if ($booking === null) {
            return null;
        }
        $detailsModel = BookingDetails::whereBookingId($id)->first();
        $roomBookings = $this->roomBookingRepository->get($id);

        return $this->buildEntityFromModel($booking, $detailsModel, $roomBookings);
    }

    public function findOrFail(BookingId $id): HotelBooking
    {
        return $this->find($id->value()) ?? throw new EntityNotFoundException('Booking not found');
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
        CreatorId $creatorId,
        BookingPeriod $period,
        ?string $note = null,
        HotelInfo $hotelInfo,
        CancelConditions $cancelConditions,
        BookingPrices $price,
        QuotaProcessingMethodEnum $quotaProcessingMethod,
    ): HotelBooking {
        return DB::transaction(
            function () use (
                $orderId,
                $creatorId,
                $period,
                $note,
                $hotelInfo,
                $cancelConditions,
                $price,
                $quotaProcessingMethod
            ) {
                $bookingModel = $this->createBase($orderId, $price, $creatorId->value());
                $booking = new HotelBooking(
                    id: new BookingId($bookingModel->id),
                    orderId: $orderId,
                    status: $bookingModel->status,
                    createdAt: $bookingModel->created_at->toImmutable(),
                    creatorId: $creatorId,
                    price: $price,
                    note: $note,
                    hotelInfo: $hotelInfo,
                    period: $period,
                    additionalInfo: null,
                    roomBookings: new RoomBookingCollection(),
                    cancelConditions: $cancelConditions,
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

    public function store(HotelBooking $booking): bool
    {
        return DB::transaction(function () use ($booking) {
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

    public function delete(BookingInterface|HotelBooking $booking): void
    {
        $this->getModel()::query()->whereId($booking->id()->value())->update([
            'status' => $booking->status(),
            'deleted_at' => now()
        ]);
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
    ): HotelBooking {
        $detailsData = $detailsModel->data;
        $additionalInfo = $detailsData['additionalInfo'] ?? null;

        return new HotelBooking(
            id: new BookingId($booking->id),
            orderId: new OrderId($booking->order_id),
            status: $booking->status,
            createdAt: $booking->created_at->toImmutable(),
            creatorId: new CreatorId($booking->creator_id),
            price: BookingPrices::fromData($booking->price),
            note: $detailsData['note'] ?? null,
            hotelInfo: HotelInfo::fromData($detailsData['hotelInfo']),
            period: BookingPeriod::fromData($detailsData['period']),
            additionalInfo: $additionalInfo !== null ? AdditionalInfo::fromData($detailsData['additionalInfo']) : null,
            roomBookings: $roomBookings,
            cancelConditions: CancelConditions::fromData($detailsData['cancelConditions']),
            quotaProcessingMethod: $detailsModel->quota_processing_method
        );
    }

    private function serializeDetails(HotelBooking $booking): array
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
