<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Infrastructure\Repository;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Infrastructure\Repository\AbstractBookingRepository as BaseRepository;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Hotel\Domain\Entity\RoomBooking;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Hotel\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelConditions;
use Module\Booking\Hotel\Domain\ValueObject\Details\HotelInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Booking\Hotel\Infrastructure\Models\Booking as Model;
use Module\Booking\Hotel\Infrastructure\Models\BookingDetails;
use Module\Shared\Domain\ValueObject\Id;

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

    public function create(
        Id $orderId,
        Id $creatorId,
        BookingPeriod $period,
        ?string $note = null,
        HotelInfo $hotelInfo,
        CancelConditions $cancelConditions
    ): Booking {
        return \DB::transaction(
            function () use ($orderId, $creatorId, $period, $note, $hotelInfo, $cancelConditions) {
                $bookingModel = $this->createBase($orderId->value(), $creatorId->value());
                $booking = new Booking(
                    id: new Id($bookingModel->id),
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
                    price: BookingPrice::buildEmpty()
                );

                BookingDetails::create([
                    'booking_id' => $booking->id()->value(),
                    'hotel_id' => $hotelInfo->id(),
                    'date_start' => $period->dateFrom(),
                    'date_end' => $period->dateTo(),
                    'nights_count' => $period->nightsCount(),
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

    private function buildEntityFromModel(
        Model $booking,
        BookingDetails $detailsModel,
        RoomBookingCollection $roomBookings
    ): Booking {
        $detailsData = $detailsModel->data;
        $additionalInfo = $detailsData['additionalInfo'] ?? null;

        return new Booking(
            id: new Id($booking->id),
            orderId: new Id($booking->order_id),
            status: $booking->status,
            createdAt: $booking->created_at->toImmutable(),
            creatorId: new Id($booking->creator_id),
            note: $detailsData['note'] ?? null,
            hotelInfo: HotelInfo::fromData($detailsData['hotelInfo']),
            period: BookingPeriod::fromData($detailsData['period']),
            additionalInfo: $additionalInfo !== null ? AdditionalInfo::fromData($detailsData['additionalInfo']) : null,
            roomBookings: $roomBookings,
            cancelConditions: CancelConditions::fromData($detailsData['cancelConditions']),
//            price: BookingPrice::fromData($detailsData['price'])
            price: $this->buildBookingPrice($roomBookings)
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
            'price' => $booking->price()->toData()
        ];
    }

    private function buildBookingPrice(RoomBookingCollection $roomBookings): BookingPrice
    {
        ['netValue' => $netValue, 'hoValue' => $hoValue, 'boValue' => $boValue] = $roomBookings->reduce(
            function (array $result, RoomBooking $roomBooking) {
                $result['netValue'] += $roomBooking->price()->netValue();
                $result['hoValue'] += $roomBooking->price()->hoValue()->value();
                $result['boValue'] += $roomBooking->price()->boValue()->value();

                return $result;
            },
            ['netValue' => 0, 'hoValue' => 0, 'boValue' => 0]
        );

        return new BookingPrice($netValue, $hoValue, $boValue);
    }
}
