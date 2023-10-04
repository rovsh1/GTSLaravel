<?php

namespace Module\Booking\Application\HotelBooking\Query;

use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Application\Dto\ReservationDto;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class FindHandler implements QueryHandlerInterface
{
    public function __construct(private readonly BookingRepositoryInterface $repository) {}

    public function handle(QueryInterface|Find $query): mixed
    {
        $model = $this->repository->find($query->id);
        if (!$model) {
            return null;
        }
        return ReservationDto::fromDomain($model);
    }
}
