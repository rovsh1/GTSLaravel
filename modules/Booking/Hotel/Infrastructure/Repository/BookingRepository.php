<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Infrastructure\Repository;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Infrastructure\Repository\AbstractBookingRepository as BaseRepository;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\CancelMarkupOption;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\CancelPeriodTypeEnum;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\DailyMarkupCollection;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\DailyMarkupOption;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelConditions;
use Module\Booking\Hotel\Domain\ValueObject\Details\HotelInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Booking\Hotel\Infrastructure\Models\Booking as Model;
use Module\Booking\Hotel\Infrastructure\Models\BookingDetails;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\Time;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    protected function getModel(): string
    {
        return Model::class;
    }

    public function find(int $id): ?BookingInterface
    {
        $booking = $this->findBase($id);
        if ($booking === null) {
            return null;
        }
        $detailsModel = BookingDetails::whereBookingId($id)->first();

        return $this->buildEntityFromModel($booking, $detailsModel);
    }

    public function get(): mixed
    {
        return $this->getModel()::query()->get();
    }

    public function create(
        int $orderId,
        int $creatorId,
        int $hotelId,
        CarbonPeriod $period,
        ?string $note = null,
        mixed $hotelDto,
        mixed $hotelMarkupSettings
    ): BookingInterface {
        $bookingModel = $this->createBase($orderId, $creatorId);
        //@todo усли изменятся дтошки, все сломается
        $cancelConditions = $this->buildCancelConditionsByCancelPeriods($hotelMarkupSettings->cancelPeriods, $period);
        $bookingPeriod = BookingPeriod::fromCarbon($period);
        $hotelInfo = new HotelInfo(
            $hotelDto->id,
            $hotelDto->name,
            new Time('14:00'), //@todo забрать из настроек отеля
            new Time('12:00'),
        );

        $booking = new Booking(
            id: new Id($bookingModel->id),
            orderId: new Id($bookingModel->order_id),
            status: $bookingModel->status,
            type: $bookingModel->type,
            createdAt: $bookingModel->created_at->toImmutable(),
            creatorId: $bookingModel->creator_id,
            roomBookings: new RoomBookingCollection(),
            cancelConditions: $cancelConditions,
            additionalInfo: null,
            hotelInfo: $hotelInfo,
            period: $bookingPeriod,
            note: $note
        );

        BookingDetails::create([
            'booking_id' => $booking->id()->value(),
            'hotel_id' => $hotelId,
            'date_start' => $bookingPeriod->dateFrom(),
            'date_end' => $bookingPeriod->dateTo(),
            'nights_count' => $bookingPeriod->nightsCount(),
            'data' => $this->serializeDetails($booking)
        ]);

        return $booking;
    }

    public function store(BookingInterface $booking): bool
    {
        $base = $this->storeBase($booking);

        $details = (bool)BookingDetails::whereBookingId($booking->id()->value())
            ->update([
                'hotel_id' => $booking->hotelInfo()->id(),
                'date_start' => $booking->period()->dateFrom(),
                'date_end' => $booking->period()->dateTo(),
                'nights_count' => $booking->period()->nightsCount(),
                'data' => $this->serializeDetails($booking)
            ]);

        return $base && $details;
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

    private function buildEntityFromModel(Model $booking, BookingDetails $detailsModel): BookingInterface
    {
        $detailsData = $detailsModel->data;
        $additionalInfo = $detailsData['additionalInfo'] ?? null;

        return new Booking(
            id: new Id($booking->id),
            orderId: new Id($booking->order_id),
            status: $booking->status,
            type: $booking->type,
            createdAt: $booking->created_at->toImmutable(),
            creatorId: new Id($booking->creator_id),
            note: $detailsData['note'] ?? null,
            hotelInfo: HotelInfo::fromData($detailsData['hotelInfo']),
            period: BookingPeriod::fromData($detailsData['period']),
            additionalInfo: $additionalInfo !== null ? AdditionalInfo::fromData($detailsData['additionalInfo']) : null,
            roomBookings: RoomBookingCollection::fromData($detailsData['roomBookings']),
            cancelConditions: CancelConditions::fromData($detailsData['cancelConditions']),
        );
    }

    private function serializeDetails(Booking $booking): array
    {
        return [
            'note' => $booking->note(),
            'hotelInfo' => $booking->hotelInfo()->toData(),
            'additionalInfo' => $booking->additionalInfo()?->toData(),
            'period' => $booking->period()->toData(),
            'roomBookings' => $booking->roomBookings()->toData(),
            'cancelConditions' => $booking->cancelConditions()->toData(),
        ];
    }

    private function buildCancelConditionsByCancelPeriods(
        array $cancelPeriods,
        CarbonPeriod $bookingPeriod
    ): CancelConditions {
        $availablePeriod = collect($cancelPeriods)->first(
            fn(mixed $cancelPeriod) => $bookingPeriod->overlaps($cancelPeriod->from, $cancelPeriod->to)
        );
        if ($availablePeriod === null) {
            //@todo понять что тут делать
            throw new \Exception('Not found cancel period for booking');
        }

        $maxDaysCount = null;
        $dailyMarkups = new DailyMarkupCollection();
        foreach ($availablePeriod->dailyMarkups as $dailyMarkup) {
            if ($dailyMarkup->daysCount > $maxDaysCount) {
                $maxDaysCount = $dailyMarkup->daysCount;
            }
            $dailyMarkups->add(
                new DailyMarkupOption(
                    new Percent($dailyMarkup->percent),
                    CancelPeriodTypeEnum::from($dailyMarkup->cancelPeriodType),
                    $dailyMarkup->daysCount
                )
            );
        }
        $cancelNoFeeDate = null;
        if ($maxDaysCount !== null) {
            $cancelNoFeeDate = $bookingPeriod->getStartDate()->clone()->subDays($maxDaysCount)->toImmutable();
        }

        return new CancelConditions(
            new CancelMarkupOption(
                new Percent($availablePeriod->noCheckInMarkup->percent),
                CancelPeriodTypeEnum::from($availablePeriod->noCheckInMarkup->cancelPeriodType)
            ),
            $dailyMarkups,
            $cancelNoFeeDate
        );
    }
}
