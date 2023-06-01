<?php

namespace Module\Booking\Hotel\Infrastructure\Repository;

use Carbon\CarbonInterface;
use Module\Booking\Hotel\Domain\Factory\BookingFactory;
use Module\Booking\Hotel\Domain\Entity\Booking as Entity;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Hotel\Infrastructure\Models\Booking as Model;

class BookingRepository implements BookingRepositoryInterface
{
    public function find(int $id): ?Entity
    {
        $model = Model::find($id);
        if (!$model) {
            return null;
        }
        return app(BookingFactory::class)->createFrom($model);
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
