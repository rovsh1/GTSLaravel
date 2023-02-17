<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use GTS\Reservation\HotelReservation\Application\Query\GetActive;
use GTS\Reservation\HotelReservation\Domain\Factory\ReservationFactory;
use GTS\Reservation\HotelReservation\Infrastructure\Models\Reservation as Model;
use GTS\Reservation\HotelReservation\Infrastructure\Models\ReservationStatusEnum;

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
