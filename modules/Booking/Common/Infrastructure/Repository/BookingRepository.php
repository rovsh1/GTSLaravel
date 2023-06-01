<?php

namespace Module\Booking\Common\Infrastructure\Repository;

use Carbon\CarbonInterface;
use Module\Booking\Common\Domain\Entity\Booking as Entity;
use Module\Booking\Common\Domain\Factory\BookingFactory;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Infrastructure\Models\Booking as Model;

class BookingRepository implements BookingRepositoryInterface
{
    public function __construct(private readonly BookingFactory $bookingFactory) {}

    public function find(int $id): ?Entity
    {
        $model = Model::withHotelDetails()->find($id);
        if (!$model) {
            return null;
        }
        return $this->bookingFactory->createFrom($model);
    }

    public function update(Entity $booking): bool
    {
        return (bool)Model::whereId($booking->id())->update([
            'order_id' => $booking->orderId(),
            'type' => $booking->type(),
            'status' => $booking->status(),
            'note' => $booking->note(),
            'creator_id' => $booking->creatorId(),
        ]);
    }

    public function searchByDateUpdate(CarbonInterface $dateUpdate, ?int $hotelId): array
    {
        $modelsQuery = Model::query()
            ->withClient()
            //todo сейчас нет даты обновления в базе
            ->where('', '>=', $dateUpdate);

        if ($hotelId !== null) {
            $modelsQuery->where('hotel_id', $hotelId);
        }

        $models = $modelsQuery->get();

        return app(BookingFactory::class)->createCollectionFrom($models);
    }

    public function searchActive(?int $hotelId): array
    {
        $modelsQuery = Model::query()
            ->withClient();
        //todo уточнить по поводу статуса у Анвара
//            ->where('reservation.status', BookingStatusEnum::WaitingConfirmation);

        if ($hotelId !== null) {
            $modelsQuery->where('hotel_id', $hotelId);
        }

        $models = $modelsQuery->get();

        return app(BookingFactory::class)->createCollectionFrom($models);
    }
}
