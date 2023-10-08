<?php

declare(strict_types=1);

namespace Module\Booking\Application\TransferBooking\UseCase\Admin;

use Module\Booking\Application\Admin\Shared\Service\StatusStorage;
use Module\Booking\Application\Admin\Shared\Support\UseCase\GetAvailableActions as Base;
use Module\Booking\Domain\Shared\Service\RequestRules;
use Module\Booking\Domain\Shared\Service\StatusRules\AdministratorRules;
use Module\Booking\Domain\TransferBooking\Repository\BookingRepositoryInterface;

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
