<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Repository;

use Carbon\CarbonInterface;
use Module\Reservation\HotelReservation\Domain\Entity\Reservation;
use Module\Reservation\HotelReservation\Domain\Entity\Reservation as Entity;
use Module\Reservation\HotelReservation\Domain\Factory\ReservationFactory;
use Module\Reservation\HotelReservation\Domain\Repository\ReservationRepositoryInterface;
use Module\Reservation\HotelReservation\Infrastructure\Models\Reservation as Model;
use Module\Reservation\HotelReservation\Infrastructure\Models\ReservationStatusEnum;

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
            //@todo сейчас нет даты обновления в базе
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
            //@todo уточнить по поводу статуса у Анвара
            ->where('reservation.status', ReservationStatusEnum::WaitingConfirmation);

        if ($hotelId !== null) {
            $modelsQuery->where('hotel_id', $hotelId);
        }

        $models = $modelsQuery->get();

        return app(ReservationFactory::class)->createCollectionFrom($models);
    }
}
