<?php

namespace GTS\Integration\Traveline\Infrastructure\Facade;

use Custom\Framework\Contracts\Bus\CommandBusInterface;

use GTS\Integration\Traveline\Application\Command\UpdateQuotasAndPlans;
use GTS\Integration\Traveline\Application\Dto\HotelDto;
use GTS\Integration\Traveline\Application\Service\HotelFinder;

class HotelFacade implements HotelFacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private HotelFinder         $hotelFinder
    ) {}

    public function getRoomsAndRatePlans(int $hotelId): HotelDto
    {
        return $this->hotelFinder->getHotelRoomsAndRatePlans($hotelId);
    }

    public function updateQuotasAndPlans(int $hotelId, array $updates)
    {
       $domainResponse = $this->commandBus->execute(new UpdateQuotasAndPlans($hotelId, $updates));

       //@todo конвертация в DTO
       return $domainResponse;
    }
}
