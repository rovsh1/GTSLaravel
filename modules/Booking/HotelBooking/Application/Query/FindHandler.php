<?php

namespace Module\Booking\HotelBooking\Application\Query;

use Module\Booking\HotelBooking\Application\Dto\ReservationDto;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
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
