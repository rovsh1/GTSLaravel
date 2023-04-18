<?php

namespace Module\Booking\Hotel\Infrastructure\Repository;

use Carbon\CarbonInterface;
use Module\Booking\Hotel\Domain\Entity\Reservation;
use Module\Booking\Hotel\Domain\Entity\Reservation as Entity;
use Module\Booking\Hotel\Domain\Factory\ReservationFactory;
use Module\Booking\Hotel\Domain\Repository\ReservationRepositoryInterface;
use Module\Booking\Hotel\Infrastructure\Models\Reservation as Model;
use Module\Booking\Hotel\Infrastructure\Models\ReservationStatusEnum;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function find(int $id): ?Reservation
    {
        $model = Model::query()
            ->withClient()
            ->find($id);

        if (!$model) {
            return null;
        }
        /** @var Entity $reservation */
        $reservation = app(ReservationFactory::class)->createFrom($model);

        return $reservation;
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

        return app(ReservationFactory::class)->createCollectionFrom($models);
    }

    public function searchActive(?int $hotelId): array
    {
        $modelsQuery = Model::query()
            ->withClient()
            //todo уточнить по поводу статуса у Анвара
            ->where('reservation.status', ReservationStatusEnum::WaitingConfirmation);

        if ($hotelId !== null) {
            $modelsQuery->where('hotel_id', $hotelId);
        }

        $models = $modelsQuery->get();

        return app(ReservationFactory::class)->createCollectionFrom($models);
    }
}
