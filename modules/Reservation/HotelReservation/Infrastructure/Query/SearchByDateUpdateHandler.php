<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Reservation\HotelReservation\Application\Query\SearchByDateUpdate;
use Module\Reservation\HotelReservation\Domain\Factory\ReservationFactory;
use Module\Reservation\HotelReservation\Infrastructure\Models\Reservation as Model;

class SearchByDateUpdateHandler implements QueryHandlerInterface
{
    /**
     * @param SearchByDateUpdate $query
     * @return void
     */
    public function handle(QueryInterface $query)
    {
        $modelsQuery = Model::withClientType()
            //@todo сейчас нет даты обновления в базе
            ->where('', '>=', $query->dateUpdate);

        if ($query->hotelId !== null) {
            $modelsQuery->where('hotel_id', $query->hotelId);
        }

        $models = $modelsQuery->get();

        return app(ReservationFactory::class)->createCollectionFrom($models);
    }
}
