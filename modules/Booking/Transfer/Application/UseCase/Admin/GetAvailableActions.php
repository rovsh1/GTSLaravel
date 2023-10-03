<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Application\UseCase\Admin;

use Module\Booking\Transfer\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Application\Support\UseCase\Admin\GetAvailableActions as Base;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\Service\StatusRules\AdministratorRules;

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
