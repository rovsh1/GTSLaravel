<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\UseCase\Admin;

use Module\Booking\Application\Shared\Service\StatusStorage;
use Module\Booking\Application\Shared\Support\UseCase\Admin\GetAvailableActions as Base;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\Service\RequestRules;
use Module\Booking\Domain\Shared\Service\StatusRules\AdministratorRules;

class GetAvailableActions extends Base
{
    public function __construct(
//        StatusRulesInterface $statusRules,
        RequestRules $requestRules,
        BookingRepositoryInterface $repository,
        StatusStorage $statusStorage
    ) {
        //@todo тут пробрасываются рулзы вручную пока
        parent::__construct(new AdministratorRules($requestRules), $requestRules, $repository, $statusStorage);
    }
}