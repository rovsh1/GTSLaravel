<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Repository;

use Carbon\CarbonInterface;
use Module\Reservation\HotelReservation\Domain\Entity\Reservation;
use Module\Reservation\HotelReservation\Domain\Entity\Reservation as Entity;
use Module\Reservation\HotelReservation\Domain\Factory\ReservationFactory;
use Module\Reservation\HotelReservation\Domain\Factory\RoomFactory;
use Module\Reservation\HotelReservation\Domain\Repository\ReservationRepositoryInterface;
use Module\Reservation\HotelReservation\Infrastructure\Models\Reservation as Model;
use Module\Reservation\HotelReservation\Infrastructure\Models\ReservationStatusEnum;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function find(int $id): ?Reservation
    {
        $model = Model::query()
            ->with([
                ...$this->getRoomsAndGuestsRelations(),
            ])
            ->withClientType()
            ->find($id);

        if (!$model) {
            return null;
        }
        /** @var Entity $reservation */
        $reservation = app(ReservationFactory::class)->createFrom($model);
        $rooms = app(RoomFactory::class)->createCollectionFrom($model->rooms);
        $reservation->appendRooms($rooms);

        return $reservation;
    }

    public function searchByDateUpdate(CarbonInterface $dateUpdate, ?int $hotelId): array
    {
        $modelsQuery = Model::query()
            ->with([
                ...$this->getRoomsAndGuestsRelations(),
            ])
            ->withClientType()
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
            ->with([
                ...$this->getRoomsAndGuestsRelations(),
            ])
            ->withClientType()
            //@todo уточнить по поводу статуса
            ->where('reservation.status', ReservationStatusEnum::Created);

        if ($hotelId !== null) {
            $modelsQuery->where('hotel_id', $hotelId);
        }

        $models = $modelsQuery->get();

        return app(ReservationFactory::class)->createCollectionFrom($models);
    }

    /**
     * @return string[]
     */
    private function getRoomsAndGuestsRelations(): array
    {
        return [
            'rooms',
            'rooms.guests',
            'rooms.checkInCondition',
            'rooms.checkOutCondition',
            'rooms.dailyPrices',
        ];
    }
}
