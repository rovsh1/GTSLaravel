<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Reservation\HotelReservation\Application\Query\GetActive;
use Module\Reservation\HotelReservation\Domain\Factory\ReservationFactory;
use Module\Reservation\HotelReservation\Infrastructure\Models\Reservation as Model;
use Module\Reservation\HotelReservation\Infrastructure\Models\ReservationStatusEnum;

class GetActiveHandler implements QueryHandlerInterface
{
    /**
     * @param GetActive $query
     * @return array
     */
    public function handle(QueryInterface $query)
    {
        $modelsQuery = Model::withClientType()
            //@todo уточнить по поводу статуса
            ->where('reservation.status', ReservationStatusEnum::Created);

        if ($query->hotelId !== null) {
            $modelsQuery->where('hotel_id', $query->hotelId);
        }

        $models = $modelsQuery->get();

        return app(ReservationFactory::class)->createCollectionFrom($models);
    }
}
