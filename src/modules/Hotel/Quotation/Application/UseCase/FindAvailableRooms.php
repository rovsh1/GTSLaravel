<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\UseCase;

use Module\Hotel\Quotation\Application\RequestDto\FindAvailableRoomsRequestDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindAvailableRooms implements UseCaseInterface
{
    public function __construct(
//        private readonly RoomRepositoryInterface $repository,
//        private readonly HotelRoomQuotaAdapterInterface $roomQuotaAdapter
    ) {
    }

    public function execute(FindAvailableRoomsRequestDto $requestDto): array
    {
    }
}
