<?php

namespace Module\Hotel\Moderation\Application\Admin\Query;

use Module\Hotel\Moderation\Application\Admin\Response\HotelDto;
use Module\Hotel\Moderation\Domain\Hotel\Repository\HotelRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class FindHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly HotelRepositoryInterface $repository
    ) {}

    /**
     * @param Find $query
     * @return HotelDto
     */
    public function handle(QueryInterface|Find $query): ?HotelDto
    {
        $hotel = $this->repository->find($query->id);
        if ($hotel === null) {
            return null;
        }

        return HotelDto::from($hotel);
    }
}
